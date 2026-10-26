<?php

if (!defined('ABSPATH')) {
    exit;
}

class VET3D_Custom_Fields_Frontend {
    
    public function __construct() {
        add_action('woocommerce_before_add_to_cart_button', array($this, 'display_custom_fields'));
        add_filter('woocommerce_add_to_cart_validation', array($this, 'validate_custom_fields'), 10, 3);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_styles'));
    }
    
    public function display_custom_fields() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        $enabled = get_post_meta($product->get_id(), '_vet3d_custom_fields_enabled', true);
        
        if ($enabled !== 'yes') {
            return;
        }
        
        $fields = get_post_meta($product->get_id(), '_vet3d_custom_fields', true);
        
        if (empty($fields) || !is_array($fields)) {
            return;
        }
        ?>
        <div class="vet3d-custom-fields-wrapper">
            <h3><?php esc_html_e('Personaliseer jouw product', 'vet3d-custom-fields'); ?></h3>
            <?php foreach ($fields as $index => $field): ?>
                <div class="vet3d-custom-field">
                    <label for="vet3d_field_<?php echo esc_attr($index); ?>">
                        <?php echo esc_html($field['label']); ?>
                        <?php if ($field['required'] === 'yes'): ?>
                            <span class="required">*</span>
                        <?php endif; ?>
                    </label>
                    
                    <?php if ($field['type'] === 'textarea'): ?>
                        <textarea 
                            id="vet3d_field_<?php echo esc_attr($index); ?>"
                            name="vet3d_custom_field[<?php echo esc_attr($index); ?>]"
                            placeholder="<?php echo esc_attr($field['placeholder']); ?>"
                            <?php if ($field['required'] === 'yes'): ?>required<?php endif; ?>
                            <?php if (!empty($field['max_length'])): ?>maxlength="<?php echo esc_attr($field['max_length']); ?>"<?php endif; ?>
                            rows="4"
                        ><?php echo isset($_POST['vet3d_custom_field'][$index]) ? esc_textarea($_POST['vet3d_custom_field'][$index]) : ''; ?></textarea>
                    <?php else: ?>
                        <input 
                            type="<?php echo esc_attr($field['type']); ?>"
                            id="vet3d_field_<?php echo esc_attr($index); ?>"
                            name="vet3d_custom_field[<?php echo esc_attr($index); ?>]"
                            value="<?php echo isset($_POST['vet3d_custom_field'][$index]) ? esc_attr($_POST['vet3d_custom_field'][$index]) : ''; ?>"
                            placeholder="<?php echo esc_attr($field['placeholder']); ?>"
                            <?php if ($field['required'] === 'yes'): ?>required<?php endif; ?>
                            <?php if (!empty($field['max_length'])): ?>maxlength="<?php echo esc_attr($field['max_length']); ?>"<?php endif; ?>
                        >
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
    
    public function validate_custom_fields($passed, $product_id, $quantity) {
        $enabled = get_post_meta($product_id, '_vet3d_custom_fields_enabled', true);
        
        if ($enabled !== 'yes') {
            return $passed;
        }
        
        $fields = get_post_meta($product_id, '_vet3d_custom_fields', true);
        
        if (empty($fields) || !is_array($fields)) {
            return $passed;
        }
        
        foreach ($fields as $index => $field) {
            if ($field['required'] === 'yes') {
                if (!isset($_POST['vet3d_custom_field'][$index]) || empty(trim($_POST['vet3d_custom_field'][$index]))) {
                    wc_add_notice(
                        sprintf(__('"%s" is een verplicht veld.', 'vet3d-custom-fields'), $field['label']),
                        'error'
                    );
                    $passed = false;
                }
            }
            
            if (isset($_POST['vet3d_custom_field'][$index]) && !empty($_POST['vet3d_custom_field'][$index])) {
                $value = $_POST['vet3d_custom_field'][$index];
                
                if ($field['type'] === 'email' && !is_email($value)) {
                    wc_add_notice(
                        sprintf(__('"%s" moet een geldig e-mailadres zijn.', 'vet3d-custom-fields'), $field['label']),
                        'error'
                    );
                    $passed = false;
                }
                
                if (!empty($field['max_length']) && strlen($value) > $field['max_length']) {
                    wc_add_notice(
                        sprintf(__('"%s" mag maximaal %d karakters bevatten.', 'vet3d-custom-fields'), $field['label'], $field['max_length']),
                        'error'
                    );
                    $passed = false;
                }
            }
        }
        
        return $passed;
    }
    
    public function enqueue_frontend_styles() {
        if (is_product()) {
            wp_add_inline_style('woocommerce-general', "
                .vet3d-custom-fields-wrapper {
                    margin: 0 0 20px 0;
                    padding: 0;
                    background: transparent;
                    border: none;
                    clear: both;
                }
                .vet3d-custom-fields-wrapper h3 {
                    margin: 0 0 15px 0;
                    font-size: 1em;
                    font-weight: 600;
                    color: #333;
                }
                .vet3d-custom-field {
                    margin-bottom: 15px;
                }
                .vet3d-custom-field:last-child {
                    margin-bottom: 0;
                }
                .vet3d-custom-field label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: 600;
                    font-size: 0.95em;
                    color: #333;
                }
                .vet3d-custom-field .required {
                    color: #e2401c;
                }
                .vet3d-custom-field input[type='text'],
                .vet3d-custom-field input[type='email'],
                .vet3d-custom-field input[type='number'],
                .vet3d-custom-field input[type='date'],
                .vet3d-custom-field textarea {
                    width: 100%;
                    max-width: 100%;
                    padding: 10px 12px;
                    border: 1px solid #ddd;
                    border-radius: 3px;
                    font-size: 1em;
                    box-sizing: border-box;
                    background: #fff;
                }
                .vet3d-custom-field input[type='text']:focus,
                .vet3d-custom-field input[type='email']:focus,
                .vet3d-custom-field input[type='number']:focus,
                .vet3d-custom-field input[type='date']:focus,
                .vet3d-custom-field textarea:focus {
                    outline: none;
                    border-color: #999;
                }
                .vet3d-custom-field textarea {
                    resize: vertical;
                    min-height: 80px;
                }
            ");
        }
    }
}
