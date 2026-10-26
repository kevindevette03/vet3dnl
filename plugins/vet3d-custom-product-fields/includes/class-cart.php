<?php

if (!defined('ABSPATH')) {
    exit;
}

class VET3D_Custom_Fields_Cart {
    
    public function __construct() {
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_custom_fields_to_cart'), 10, 2);
        add_filter('woocommerce_get_item_data', array($this, 'display_custom_fields_in_cart'), 10, 2);
        add_action('woocommerce_before_calculate_totals', array($this, 'recalculate_cart'));
    }
    
    public function add_custom_fields_to_cart($cart_item_data, $product_id) {
        $enabled = get_post_meta($product_id, '_vet3d_custom_fields_enabled', true);
        
        if ($enabled !== 'yes') {
            return $cart_item_data;
        }
        
        $fields = get_post_meta($product_id, '_vet3d_custom_fields', true);
        
        if (empty($fields) || !is_array($fields)) {
            return $cart_item_data;
        }
        
        $custom_data = array();
        
        foreach ($fields as $index => $field) {
            if (isset($_POST['vet3d_custom_field'][$index]) && !empty($_POST['vet3d_custom_field'][$index])) {
                $custom_data[] = array(
                    'label' => $field['label'],
                    'value' => sanitize_text_field($_POST['vet3d_custom_field'][$index])
                );
            }
        }
        
        if (!empty($custom_data)) {
            $cart_item_data['vet3d_custom_fields'] = $custom_data;
            $cart_item_data['unique_key'] = md5(json_encode($custom_data) . time());
        }
        
        return $cart_item_data;
    }
    
    public function display_custom_fields_in_cart($item_data, $cart_item) {
        if (isset($cart_item['vet3d_custom_fields']) && is_array($cart_item['vet3d_custom_fields'])) {
            foreach ($cart_item['vet3d_custom_fields'] as $field) {
                $item_data[] = array(
                    'name' => $field['label'],
                    'value' => $field['value']
                );
            }
        }
        
        return $item_data;
    }
    
    public function recalculate_cart($cart) {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }
        
        if (did_action('woocommerce_before_calculate_totals') >= 2) {
            return;
        }
    }
}
