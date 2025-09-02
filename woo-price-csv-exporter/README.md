# Woo Price CSV Exporter

Export WooCommerce products into a clean CSV file with **price range** and **category filters**.  
Built for store managers who need quick access to product data in Excel, Google Sheets, or any other CSV-compatible tool.

---

## ✨ Features

- Export products by **minimum and maximum price**.
- Filter by one or more **WooCommerce categories**.
- Outputs CSV with the following fields:
  - Product ID  
  - SKU  
  - Name  
  - Current Price  
  - Regular Price  
  - Sale Price  
  - Stock Status  
  - Product URL
- Download starts instantly in your browser (no need to fetch files manually).
- Works seamlessly in the WordPress Admin panel.

---

## 📋 Requirements

- WordPress 6.0+  
- WooCommerce 7.0+  
- PHP 7.4 or higher  

---

## 🔧 Installation

1. Download or clone this repository.  
2. Upload the folder into your WordPress site under `wp-content/plugins/`.  
3. Activate the plugin from **Plugins → Installed Plugins**.  
4. Make sure WooCommerce is active (the plugin will show a notice if not).  

---

## 🚀 Usage

1. Go to **Admin → Price Export** in your WordPress dashboard.  
2. Select **Min Price** and **Max Price**.  
3. (Optional) Check one or more categories to filter products.  
4. Click **Export to CSV**.  
5. A `.csv` file will automatically download to your computer.  

---

## 📂 File Structure

woo-price-csv-exporter/
├── woo-price-csv-exporter.php # Main plugin file
└── includes/
    └── class-category-filter.php # Category filter logic
└── readme.md


---

## 👨‍💻 Authors

- **alex** — development, testing, enhancements  
- **ChatGPT** — base structure, logic generation  

This plugin is a result of **co-creation between a human developer and AI**, where *alex* shaped, tested, and refined the solution, while ChatGPT provided initial scaffolding and code suggestions.

---

## 📜 License

This plugin is licensed under the [GPL v2 or later](https://www.gnu.org/licenses/gpl-2.0.html).  
You are free to use, modify, and distribute it.

---

## 📝 Notes

- This is not an official WooCommerce extension.  
- Always test exports on a staging site before production use.  



