<?php
/**
 * Plugin Name: Woo Price CSV Exporter
 * Description: Export WooCommerce products to CSV with price range and category filter.
 * Version: 1.3
 * Author: alex (development, testing, enhancements) & ChatGPT (base structure, logic generation)
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Include the category filter class
require_once plugin_dir_path(__FILE__) . 'includes/class-category-filter.php';

class WooPriceCSVExporter {
    public function __construct() {
        add_action('admin_init', [$this, 'check_dependencies']);
        add_action('admin_menu', [$this, 'add_menu']);
    }

    // Check if WooCommerce is active
    public function check_dependencies() {
        if ( ! class_exists('WooCommerce') ) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p><strong>Woo Price CSV Exporter</strong> requires <strong>WooCommerce</strong> to be installed and active.</p></div>';
            });
            // Remove plugin menu if WooCommerce is not active
            remove_action('admin_menu', [$this, 'add_menu']);
        }
    }

    public function add_menu() {
        add_menu_page(
            'Product Price Export',
            'Price Export',
            'manage_woocommerce',
            'woo-price-exporter',
            [$this, 'settings_page'],
            'dashicons-download',
            56
        );
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>Export Products by Price</h1>
            <form method="post">
                <?php wp_nonce_field('woo_price_exporter_action','woo_price_exporter_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Min Price</th>
                        <td><input type="number" name="min_price" value="<?php echo isset($_POST['min_price']) ? esc_attr($_POST['min_price']) : '0'; ?>"></td>
                    </tr>
                    <tr>
                        <th scope="row">Max Price</th>
                        <td><input type="number" name="max_price" value="<?php echo isset($_POST['max_price']) ? esc_attr($_POST['max_price']) : '1000'; ?>"></td>
                    </tr>
                </table>

                <?php
                // Render category checkboxes using the class
                WC_Category_Filter::render_checkboxes( WC_Category_Filter::get_selected_from_post() );
                ?>

                <p class="submit">
                    <input type="submit" name="price_action" class="button-primary" value="Export to CSV">
                </p>
            </form>
        </div>
        <?php

        if (
            isset($_POST['woo_price_exporter_nonce']) &&
            wp_verify_nonce($_POST['woo_price_exporter_nonce'], 'woo_price_exporter_action')
        ) {
            $this->process_export();
        }
    }

    private function process_export() {
        global $wpdb;

        $min = isset($_POST['min_price']) ? floatval($_POST['min_price']) : 0;
        $max = isset($_POST['max_price']) ? floatval($_POST['max_price']) : 999999;

        // Prepare meta query for price
        $meta_query = [
            [
                'key' => '_price',
                'value' => [$min, $max],
                'compare' => 'BETWEEN',
                'type' => 'DECIMAL'
            ]
        ];

        // Category filter
        $cat_ids = WC_Category_Filter::get_selected_from_post();

        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => $meta_query,
        ];

        if (!empty($cat_ids)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $cat_ids,
                ]
            ];
        }

        $q = new WP_Query($args);
        $ids = $q->posts;

        if ($_POST['price_action'] === 'Export to CSV' && !empty($ids)) {
            if (ob_get_length()) ob_end_clean();

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=products_' . date('Ymd_His') . '.csv');
            header('Pragma: no-cache');
            header('Expires: 0');

            $fp = fopen('php://output', 'w');
            fputcsv($fp, ['ID','SKU','Name','Current Price','Regular Price','Sale Price','Stock Status','URL']);

            foreach ($ids as $pid) {
                $p = wc_get_product($pid);
                if (!$p) continue;
                fputcsv($fp, [
                    $pid,
                    $p->get_sku(),
                    $p->get_name(),
                    $p->get_price(),
                    $p->get_regular_price(),
                    $p->get_sale_price(),
                    $p->get_stock_status(),
                    get_permalink($pid),
                ]);
            }

            fclose($fp);
            exit;
        } else {
            echo '<div class="notice notice-warning"><p>No products found for the selected filters.</p></div>';
        }
    }
}

new WooPriceCSVExporter();
