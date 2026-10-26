<?php

if (!defined('ABSPATH')) {
    exit;
}

class VET3D_Custom_Fields_Admin {
    
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_product_meta_box'));
        add_action('woocommerce_process_product_meta', array($this, 'save_custom_fields'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    public function add_product_meta_box() {
        add_meta_box(
            'vet3d_custom_fields',
            __('Custom Personalisatie Velden', 'vet3d-custom-fields'),
            array($this, 'render_meta_box'),
            'product',
            'normal',
            'high'
        );
    }
    
    public function render_meta_box($post) {
        wp_nonce_field('vet3d_custom_fields_nonce', 'vet3d_custom_fields_nonce');
        
        $enabled = get_post_meta($post->ID, '_vet3d_custom_fields_enabled', true);
        $fields = get_post_meta($post->ID, '_vet3d_custom_fields', true);
        
        if (!is_array($fields)) {
            $fields = array();
        }
        ?>
        <div class="vet3d-custom-fields-admin">
            <p>
                <label>
                    <input type="checkbox" name="vet3d_custom_fields_enabled" value="yes" <?php checked($enabled, 'yes'); ?>>
                    <?php esc_html_e('Activeer custom velden voor dit product', 'vet3d-custom-fields'); ?>
                </label>
            </p>
            
            <div id="vet3d-fields-container" style="<?php echo ($enabled !== 'yes') ? 'display:none;' : ''; ?>">
                <table class="widefat vet3d-fields-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Veldnaam', 'vet3d-custom-fields'); ?></th>
                            <th><?php esc_html_e('Type', 'vet3d-custom-fields'); ?></th>
                            <th><?php esc_html_e('Verplicht', 'vet3d-custom-fields'); ?></th>
                            <th><?php esc_html_e('Placeholder', 'vet3d-custom-fields'); ?></th>
                            <th><?php esc_html_e('Max lengte', 'vet3d-custom-fields'); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="vet3d-fields-list">
                        <?php
                        if (!empty($fields)) {
                            foreach ($fields as $index => $field) {
                                $this->render_field_row($index, $field);
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <p>
                    <button type="button" class="button" id="vet3d-add-field">
                        <?php esc_html_e('+ Veld Toevoegen', 'vet3d-custom-fields'); ?>
                    </button>
                </p>
            </div>
        </div>
        
        <script type="text/template" id="vet3d-field-template">
            <?php $this->render_field_row('{{INDEX}}', array()); ?>
        </script>
        <?php
    }
    
    private function render_field_row($index, $field = array()) {
        $field = wp_parse_args($field, array(
            'label' => '',
            'type' => 'text',
            'required' => 'no',
            'placeholder' => '',
            'max_length' => ''
        ));
        ?>
        <tr class="vet3d-field-row" data-index="<?php echo esc_attr($index); ?>">
            <td>
                <input type="text" name="vet3d_fields[<?php echo esc_attr($index); ?>][label]" 
                       value="<?php echo esc_attr($field['label']); ?>" 
                       placeholder="Naam" style="width:100%;">
            </td>
            <td>
                <select name="vet3d_fields[<?php echo esc_attr($index); ?>][type]" style="width:100%;">
                    <option value="text" <?php selected($field['type'], 'text'); ?>>Text</option>
                    <option value="textarea" <?php selected($field['type'], 'textarea'); ?>>Textarea</option>
                    <option value="number" <?php selected($field['type'], 'number'); ?>>Nummer</option>
                    <option value="date" <?php selected($field['type'], 'date'); ?>>Datum</option>
                    <option value="email" <?php selected($field['type'], 'email'); ?>>Email</option>
                </select>
            </td>
            <td>
                <input type="checkbox" name="vet3d_fields[<?php echo esc_attr($index); ?>][required]" 
                       value="yes" <?php checked($field['required'], 'yes'); ?>>
            </td>
            <td>
                <input type="text" name="vet3d_fields[<?php echo esc_attr($index); ?>][placeholder]" 
                       value="<?php echo esc_attr($field['placeholder']); ?>" 
                       placeholder="bijv. Jouw naam" style="width:100%;">
            </td>
            <td>
                <input type="number" name="vet3d_fields[<?php echo esc_attr($index); ?>][max_length]" 
                       value="<?php echo esc_attr($field['max_length']); ?>" 
                       placeholder="100" style="width:80px;" min="1">
            </td>
            <td>
                <button type="button" class="button vet3d-remove-field" style="color:#a00;">
                    <?php esc_html_e('Verwijder', 'vet3d-custom-fields'); ?>
                </button>
            </td>
        </tr>
        <?php
    }
    
    public function save_custom_fields($post_id) {
        if (!isset($_POST['vet3d_custom_fields_nonce']) || 
            !wp_verify_nonce($_POST['vet3d_custom_fields_nonce'], 'vet3d_custom_fields_nonce')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $enabled = isset($_POST['vet3d_custom_fields_enabled']) ? 'yes' : 'no';
        update_post_meta($post_id, '_vet3d_custom_fields_enabled', $enabled);
        
        $fields = array();
        if (isset($_POST['vet3d_fields']) && is_array($_POST['vet3d_fields'])) {
            foreach ($_POST['vet3d_fields'] as $field) {
                if (!empty($field['label'])) {
                    $fields[] = array(
                        'label' => sanitize_text_field($field['label']),
                        'type' => sanitize_text_field($field['type']),
                        'required' => isset($field['required']) ? 'yes' : 'no',
                        'placeholder' => sanitize_text_field($field['placeholder']),
                        'max_length' => absint($field['max_length'])
                    );
                }
            }
        }
        
        update_post_meta($post_id, '_vet3d_custom_fields', $fields);
    }
    
    public function enqueue_admin_scripts($hook) {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }
        
        global $post;
        if (!$post || 'product' !== $post->post_type) {
            return;
        }
        
        wp_add_inline_script('jquery', "
            jQuery(document).ready(function($) {
                var fieldIndex = $('.vet3d-field-row').length;
                
                $('input[name=\"vet3d_custom_fields_enabled\"]').on('change', function() {
                    $('#vet3d-fields-container').toggle(this.checked);
                });
                
                $('#vet3d-add-field').on('click', function() {
                    var template = $('#vet3d-field-template').html();
                    var newField = template.replace(/{{INDEX}}/g, fieldIndex);
                    $('#vet3d-fields-list').append(newField);
                    fieldIndex++;
                });
                
                $(document).on('click', '.vet3d-remove-field', function() {
                    $(this).closest('tr').remove();
                });
            });
        ");
    }
}
