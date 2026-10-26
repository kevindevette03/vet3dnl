<?php

if (!defined('ABSPATH')) {
    exit;
}

class VET3D_Custom_Fields_Order {
    
    public function __construct() {
        add_action('woocommerce_checkout_create_order_line_item', array($this, 'add_custom_fields_to_order'), 10, 4);
        add_action('woocommerce_order_item_meta_end', array($this, 'display_custom_fields_in_order'), 10, 4);
        add_action('woocommerce_admin_order_item_headers', array($this, 'admin_order_item_headers'));
        add_action('woocommerce_admin_order_item_values', array($this, 'admin_order_item_values'), 10, 3);
        add_filter('woocommerce_email_order_item_meta_fields', array($this, 'add_custom_fields_to_email'), 10, 3);
    }
    
    public function add_custom_fields_to_order($item, $cart_item_key, $values, $order) {
        if (isset($values['vet3d_custom_fields']) && is_array($values['vet3d_custom_fields'])) {
            foreach ($values['vet3d_custom_fields'] as $field) {
                $item->add_meta_data($field['label'], $field['value'], true);
            }
        }
    }
    
    public function display_custom_fields_in_order($item_id, $item, $order, $plain_text) {
        $custom_fields = array();
        
        foreach ($item->get_meta_data() as $meta) {
            $data = $meta->get_data();
            if (strpos($data['key'], '_') !== 0) {
                $custom_fields[] = $data;
            }
        }
        
        if (!empty($custom_fields) && !$plain_text) {
            echo '<div class="vet3d-order-custom-fields" style="margin-top:10px; padding:10px; background:#f9f9f9; border-radius:3px;">';
            echo '<strong>' . esc_html__('Personalisatie:', 'vet3d-custom-fields') . '</strong><br>';
            foreach ($custom_fields as $field) {
                echo '<small>' . esc_html($field['key']) . ': ' . esc_html($field['value']) . '</small><br>';
            }
            echo '</div>';
        }
    }
    
    public function admin_order_item_headers() {
        echo '<th class="vet3d-custom-fields-header">' . esc_html__('Personalisatie', 'vet3d-custom-fields') . '</th>';
    }
    
    public function admin_order_item_values($product, $item, $item_id) {
        echo '<td class="vet3d-custom-fields-data">';
        
        $custom_fields = array();
        foreach ($item->get_meta_data() as $meta) {
            $data = $meta->get_data();
            if (strpos($data['key'], '_') !== 0) {
                $custom_fields[] = $data;
            }
        }
        
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $field) {
                echo '<div style="margin:2px 0;">';
                echo '<strong>' . esc_html($field['key']) . ':</strong> ';
                echo esc_html($field['value']);
                echo '</div>';
            }
        } else {
            echo '-';
        }
        
        echo '</td>';
    }
    
    public function add_custom_fields_to_email($fields, $sent_to_admin, $item) {
        $custom_fields = array();
        
        foreach ($item->get_meta_data() as $meta) {
            $data = $meta->get_data();
            if (strpos($data['key'], '_') !== 0) {
                $custom_fields[] = array(
                    'key' => $data['key'],
                    'label' => $data['key'],
                    'value' => $data['value']
                );
            }
        }
        
        if (!empty($custom_fields)) {
            return array_merge($fields, $custom_fields);
        }
        
        return $fields;
    }
}
