<?php
/**
 * Plugin Name: VET3D Custom Product Fields
 * Plugin URI: https://vet3d.nl
 * Description: Voeg custom velden toe aan WooCommerce productpagina's voor gepersonaliseerde bestellingen (naam, datum, etc.)
 * Version: 1.0.0
 * Author: VET3D
 * Author URI: https://vet3d.nl
 * Text Domain: vet3d-custom-fields
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('VET3D_CUSTOM_FIELDS_VERSION')) {
    define('VET3D_CUSTOM_FIELDS_VERSION', '1.0.0');
}

if (!defined('VET3D_CUSTOM_FIELDS_PLUGIN_DIR')) {
    define('VET3D_CUSTOM_FIELDS_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

class VET3D_Custom_Product_Fields {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }
    
    public function init() {
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            return;
        }
        
        require_once VET3D_CUSTOM_FIELDS_PLUGIN_DIR . 'includes/class-admin.php';
        require_once VET3D_CUSTOM_FIELDS_PLUGIN_DIR . 'includes/class-frontend.php';
        require_once VET3D_CUSTOM_FIELDS_PLUGIN_DIR . 'includes/class-cart.php';
        require_once VET3D_CUSTOM_FIELDS_PLUGIN_DIR . 'includes/class-order.php';
        
        new VET3D_Custom_Fields_Admin();
        new VET3D_Custom_Fields_Frontend();
        new VET3D_Custom_Fields_Cart();
        new VET3D_Custom_Fields_Order();
    }
    
    public function woocommerce_missing_notice() {
        ?>
        <div class="error">
            <p><?php esc_html_e('VET3D Custom Product Fields vereist WooCommerce om te werken. Installeer en activeer WooCommerce eerst.', 'vet3d-custom-fields'); ?></p>
        </div>
        <?php
    }
}

function vet3d_custom_fields() {
    return VET3D_Custom_Product_Fields::instance();
}

vet3d_custom_fields();
