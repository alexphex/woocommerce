<?php
if ( ! defined('ABSPATH') ) exit;

class WC_Category_Filter {

    // Get All WooCommerce Categories
    public static function get_all_categories() {
        $terms = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ]);
        return $terms;
    }

    // Display category checkboxes
    public static function render_checkboxes($selected = []) {
        $categories = self::get_all_categories();
        if (empty($categories)) return;

        echo '<fieldset><legend>Filter by category</legend>';
        foreach ($categories as $cat) {
            $checked = in_array($cat->term_id, $selected) ? 'checked' : '';
            echo '<label style="display:block;margin-bottom:3px;">';
            echo '<input type="checkbox" name="product_cats[]" value="' . esc_attr($cat->term_id) . '" ' . $checked . '> ';
            echo esc_html($cat->name);
            echo '</label>';
        }
        echo '</fieldset>';
    }

    // Returns an array of IDs of the selected categories from POST
    public static function get_selected_from_post() {
        return isset($_POST['product_cats']) ? array_map('intval', $_POST['product_cats']) : [];
    }
}

