<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Het thema moet ook werken (zonder shop) zolang WooCommerce niet actief is.
function vet3d_wc_active() {
    return class_exists( 'WooCommerce' );
}

add_action( 'after_setup_theme', function () {
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'align-wide' );
    // WooCommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
} );

add_action( 'init', function () {
    register_block_pattern_category( 'vet3d', array( 'label' => __( 'Vet!3D', 'vet3d' ) ) );
} );

// Registreer navigatie menu's
add_action( 'after_setup_theme', function () {
    register_nav_menus( array(
        'primary' => __( 'Primaire Navigatie', 'vet3d' ),
    ) );
}, 5 );

// Block-themas laden style.css niet automatisch op de front-end.
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'vet3d-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
    
    // JavaScript voor product gallery slider en sticky cart
    if ( vet3d_wc_active() && is_product() ) {
        wp_enqueue_script( 'vet3d-product', get_template_directory_uri() . '/assets/js/product.js', array('jquery'), wp_get_theme()->get( 'Version' ), true );
    }
    
    // 3D Viewer op shop/archive pagina's
    if ( is_archive() || ( vet3d_wc_active() && is_shop() ) ) {
        wp_enqueue_script( 'vet3d-3d-viewer', get_template_directory_uri() . '/assets/js/3d-viewer.js', array(), wp_get_theme()->get( 'Version' ), true );
    }
    
    // Lookbook 3D Viewer op homepage en lookbook pagina
    if ( is_front_page() || is_page_template( 'page-lookbook.html' ) ) {
        wp_enqueue_script( 'vet3d-lookbook-viewer', get_template_directory_uri() . '/assets/js/lookbook-viewer.js', array(), wp_get_theme()->get( 'Version' ), true );
    }

    // Scroll-gestuurde video op de homepage
    if ( is_front_page() ) {
        wp_enqueue_script( 'vet3d-scroll-video', get_template_directory_uri() . '/assets/js/scroll-video.js', array(), wp_get_theme()->get( 'Version' ), true );
    }
} );

// Voeg type="module" toe aan 3D viewer scripts
add_filter( 'script_loader_tag', function( $tag, $handle ) {
    if ( in_array( $handle, array( 'vet3d-3d-viewer', 'vet3d-lookbook-viewer' ) ) ) {
        $tag = str_replace( '<script ', '<script type="module" ', $tag );
    }
    return $tag;
}, 10, 2 );

// Voeg import map toe voor Three.js modules
add_action( 'wp_head', function() {
    if ( is_front_page() || is_page_template( 'page-lookbook.html' ) || is_archive() || ( vet3d_wc_active() && is_shop() ) ) {
        ?>
        <script type="importmap">
        {
            "imports": {
                "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
                "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
            }
        }
        </script>
        <?php
    }
}, 1 );

// Product category bottom SEO text with WYSIWYG editor
add_action( 'product_cat_add_form_fields', function () {
    ?>
    <div class="form-field category-bottom-text-wrap">
        <label for="category_bottom_text"><?php _e( 'SEO tekst onderin', 'vet3d' ); ?></label>
        <?php
        wp_editor( '', 'category_bottom_text', array(
            'textarea_name' => 'category_bottom_text',
            'textarea_rows' => 10,
            'media_buttons' => true,
            'teeny' => false,
            'tinymce' => array(
                'toolbar1' => 'formatselect,bold,italic,bullist,numlist,link,unlink,blockquote,alignleft,aligncenter,alignright,undo,redo',
            ),
        ) );
        ?>
        <p><?php _e( 'Deze tekst wordt onder de producten weergegeven (SEO)', 'vet3d' ); ?></p>
    </div>
    <?php
} );

add_action( 'product_cat_edit_form_fields', function ( $term ) {
    $bottom_text = get_term_meta( $term->term_id, 'category_bottom_text', true );
    ?>
    <tr class="form-field category-bottom-text-wrap">
        <th scope="row" valign="top"><label for="category_bottom_text"><?php _e( 'SEO tekst onderin', 'vet3d' ); ?></label></th>
        <td>
            <?php
            wp_editor( $bottom_text, 'category_bottom_text_' . $term->term_id, array(
                'textarea_name' => 'category_bottom_text',
                'textarea_rows' => 10,
                'media_buttons' => true,
                'teeny' => false,
                'tinymce' => array(
                    'toolbar1' => 'formatselect,bold,italic,bullist,numlist,link,unlink,blockquote,alignleft,aligncenter,alignright,undo,redo',
                ),
            ) );
            ?>
            <p class="description"><?php _e( 'Deze tekst wordt onder de producten weergegeven (SEO)', 'vet3d' ); ?></p>
        </td>
    </tr>
    <?php
} );

add_action( 'edited_product_cat', function ( $term_id ) {
    if ( isset( $_POST['category_bottom_text'] ) ) {
        update_term_meta( $term_id, 'category_bottom_text', wp_kses_post( $_POST['category_bottom_text'] ) );
    }
} );

add_action( 'create_product_cat', function ( $term_id ) {
    if ( isset( $_POST['category_bottom_text'] ) ) {
        add_term_meta( $term_id, 'category_bottom_text', wp_kses_post( $_POST['category_bottom_text'] ), true );
    }
} );

// Display category description at top and bottom text
add_action( 'woocommerce_archive_description', function () {
    if ( is_product_category() ) {
        $term = get_queried_object();
        if ( $term && ! empty( $term->description ) ) {
            echo '<div class="term-description category-top-text">' . wpautop( do_shortcode( $term->description ) ) . '</div>';
        }
    }
}, 5 );

add_action( 'woocommerce_after_main_content', function () {
    if ( is_product_category() ) {
        $term = get_queried_object();
        if ( $term ) {
            $bottom_text = get_term_meta( $term->term_id, 'category_bottom_text', true );
            if ( ! empty( $bottom_text ) ) {
                echo '<div class="category-bottom-text">' . wpautop( do_shortcode( $bottom_text ) ) . '</div>';
            }
        }
    }
}, 5 );

// Remove reviews tab from product page
add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
    unset( $tabs['reviews'] );
    return $tabs;
}, 98 );

// Sticky cart HTML
add_action( 'wp_footer', function () {
    if ( ! vet3d_wc_active() || ! is_product() ) {
        return;
    }
    
    global $product;
    if ( ! $product ) {
        return;
    }
    
    $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'thumbnail' );
    $image_url = $thumbnail ? $thumbnail[0] : wc_placeholder_img_src();
    ?>
    <div class="vet3d-sticky-cart">
        <div class="product-info">
            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
            <div class="details">
                <h4><?php echo esc_html( $product->get_name() ); ?></h4>
                <div class="price"><?php echo $product->get_price_html(); ?></div>
            </div>
        </div>
        <div class="add-action">
            <div class="quantity">
                <button type="button" class="minus">−</button>
                <input type="number" class="qty" value="1" min="1" max="<?php echo $product->get_max_purchase_quantity(); ?>">
                <button type="button" class="plus">+</button>
            </div>
            <button class="add-btn single_add_to_cart_button">
                <?php echo esc_html( $product->is_type( 'simple' ) ? 'In winkelwagen' : 'Selecteer opties' ); ?>
            </button>
        </div>
    </div>
    <?php
} );

// Custom Post Type voor Lookbook items
add_action( 'init', function () {
    register_post_type( 'lookbook', array(
        'labels' => array(
            'name' => __( 'Lookbook', 'vet3d' ),
            'singular_name' => __( 'Lookbook Item', 'vet3d' ),
            'add_new' => __( 'Nieuw toevoegen', 'vet3d' ),
            'add_new_item' => __( 'Nieuw lookbook item toevoegen', 'vet3d' ),
            'edit_item' => __( 'Bewerk lookbook item', 'vet3d' ),
            'new_item' => __( 'Nieuw lookbook item', 'vet3d' ),
            'view_item' => __( 'Bekijk lookbook item', 'vet3d' ),
            'search_items' => __( 'Zoek lookbook items', 'vet3d' ),
            'not_found' => __( 'Geen lookbook items gevonden', 'vet3d' ),
        ),
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-format-gallery',
        'supports' => array( 'title', 'thumbnail' ),
        'show_in_rest' => true,
    ) );
} );

// Meta veld voor lookbook tile size en STL file
add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'lookbook_settings',
        __( 'Lookbook Instellingen', 'vet3d' ),
        function ( $post ) {
            wp_nonce_field( 'lookbook_meta', 'lookbook_meta_nonce' );
            $size = get_post_meta( $post->ID, '_lookbook_size', true );
            $dark_text = get_post_meta( $post->ID, '_lookbook_dark_text', true );
            $stl_file = get_post_meta( $post->ID, '_lookbook_stl_file', true );
            ?>
            <p>
                <label><?php _e( 'Tegel formaat:', 'vet3d' ); ?></label><br>
                <select name="lookbook_size" style="width:100%">
                    <option value=""><?php _e( 'Normaal', 'vet3d' ); ?></option>
                    <option value="tile-big" <?php selected( $size, 'tile-big' ); ?>><?php _e( 'Groot', 'vet3d' ); ?></option>
                    <option value="tile-tall" <?php selected( $size, 'tile-tall' ); ?>><?php _e( 'Hoog', 'vet3d' ); ?></option>
                    <option value="tile-wide" <?php selected( $size, 'tile-wide' ); ?>><?php _e( 'Breed', 'vet3d' ); ?></option>
                </select>
            </p>
            <p>
                <label>
                    <input type="checkbox" name="lookbook_dark_text" value="1" <?php checked( $dark_text, '1' ); ?>>
                    <?php _e( 'Gebruik donkere tekst', 'vet3d' ); ?>
                </label>
            </p>
            <p>
                <label><?php _e( '3D Model (STL):', 'vet3d' ); ?></label><br>
                <input type="text" id="lookbook_stl_file" name="lookbook_stl_file" value="<?php echo esc_attr( $stl_file ); ?>" style="width: 100%;" placeholder="<?php _e( 'STL, 3MF of OBJ bestand URL', 'vet3d' ); ?>" />
                <button type="button" class="button upload_lookbook_stl_button" style="margin-top: 5px;"><?php _e( 'Upload 3D Model', 'vet3d' ); ?></button>
                <span class="description" style="display: block; margin-top: 5px;">
                    <?php _e( 'Upload een 3D bestand (STL, 3MF of OBJ) om 3D preview te tonen bij hover.', 'vet3d' ); ?>
                </span>
            </p>
            <script>
            jQuery(function($) {
                var lookbookStlUploader;
                
                $('.upload_lookbook_stl_button').on('click', function(e) {
                    e.preventDefault();
                    
                    if (lookbookStlUploader) {
                        lookbookStlUploader.open();
                        return;
                    }
                    
                    lookbookStlUploader = wp.media({
                        title: '<?php _e( 'Kies 3D Model', 'vet3d' ); ?>',
                        button: {
                            text: '<?php _e( 'Gebruik dit bestand', 'vet3d' ); ?>'
                        },
                        library: {
                            type: ['model/stl', 'model/3mf', 'model/obj', 'application/sla', 'application/octet-stream']
                        },
                        multiple: false
                    });
                    
                    lookbookStlUploader.on('select', function() {
                        var attachment = lookbookStlUploader.state().get('selection').first().toJSON();
                        $('#lookbook_stl_file').val(attachment.url);
                    });
                    
                    lookbookStlUploader.open();
                });
            });
            </script>
            <?php
        },
        'lookbook',
        'side'
    );
} );

add_action( 'save_post_lookbook', function ( $post_id ) {
    if ( ! isset( $_POST['lookbook_meta_nonce'] ) || ! wp_verify_nonce( $_POST['lookbook_meta_nonce'], 'lookbook_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['lookbook_size'] ) ) {
        update_post_meta( $post_id, '_lookbook_size', sanitize_text_field( $_POST['lookbook_size'] ) );
    }
    if ( isset( $_POST['lookbook_dark_text'] ) ) {
        update_post_meta( $post_id, '_lookbook_dark_text', '1' );
    } else {
        delete_post_meta( $post_id, '_lookbook_dark_text' );
    }
    if ( isset( $_POST['lookbook_stl_file'] ) ) {
        update_post_meta( $post_id, '_lookbook_stl_file', esc_url_raw( $_POST['lookbook_stl_file'] ) );
    }
} );

// Helper functie voor hero opties
function vet3d_get_hero_option( $key, $default = '' ) {
    return get_option( 'vet3d_hero_' . $key, $default );
}

// Hero instellingen pagina
add_action( 'admin_menu', function () {
    add_theme_page(
        __( 'Hero Sectie', 'vet3d' ),
        __( 'Hero Sectie', 'vet3d' ),
        'manage_options',
        'vet3d-hero-settings',
        function () {
            if ( isset( $_POST['vet3d_hero_submit'] ) && check_admin_referer( 'vet3d_hero_settings' ) ) {
                update_option( 'vet3d_hero_pill', sanitize_text_field( $_POST['hero_pill'] ) );
                update_option( 'vet3d_hero_title', sanitize_text_field( $_POST['hero_title'] ) );
                update_option( 'vet3d_hero_subtitle', sanitize_textarea_field( $_POST['hero_subtitle'] ) );
                update_option( 'vet3d_hero_btn1_text', sanitize_text_field( $_POST['hero_btn1_text'] ) );
                update_option( 'vet3d_hero_btn1_url', esc_url_raw( $_POST['hero_btn1_url'] ) );
                update_option( 'vet3d_hero_btn2_text', sanitize_text_field( $_POST['hero_btn2_text'] ) );
                update_option( 'vet3d_hero_btn2_url', esc_url_raw( $_POST['hero_btn2_url'] ) );
                echo '<div class="notice notice-success"><p>' . __( 'Instellingen opgeslagen!', 'vet3d' ) . '</p></div>';
            }
            ?>
            <div class="wrap">
                <h1><?php _e( 'Hero Sectie Instellingen', 'vet3d' ); ?></h1>
                <form method="post">
                    <?php wp_nonce_field( 'vet3d_hero_settings' ); ?>
                    <table class="form-table">
                        <tr>
                            <th><label for="hero_pill"><?php _e( 'Label (pill)', 'vet3d' ); ?></label></th>
                            <td><input type="text" id="hero_pill" name="hero_pill" value="<?php echo esc_attr( vet3d_get_hero_option( 'pill', '3D-printen op maat' ) ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="hero_title"><?php _e( 'Hoofdtitel', 'vet3d' ); ?></label></th>
                            <td><input type="text" id="hero_title" name="hero_title" value="<?php echo esc_attr( vet3d_get_hero_option( 'title', 'Vette prints, precies zoals jij ze wilt.' ) ); ?>" class="large-text"></td>
                        </tr>
                        <tr>
                            <th><label for="hero_subtitle"><?php _e( 'Ondertitel', 'vet3d' ); ?></label></th>
                            <td><textarea id="hero_subtitle" name="hero_subtitle" rows="3" class="large-text"><?php echo esc_textarea( vet3d_get_hero_option( 'subtitle', 'Kant-en-klare ontwerpen of jouw eigen STL-bestand. Wij printen het scherp, snel en in elke kleur.' ) ); ?></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="hero_btn1_text"><?php _e( 'Knop 1 - Tekst', 'vet3d' ); ?></label></th>
                            <td><input type="text" id="hero_btn1_text" name="hero_btn1_text" value="<?php echo esc_attr( vet3d_get_hero_option( 'btn1_text', 'Bekijk het lookbook' ) ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="hero_btn1_url"><?php _e( 'Knop 1 - URL', 'vet3d' ); ?></label></th>
                            <td><input type="url" id="hero_btn1_url" name="hero_btn1_url" value="<?php echo esc_url( vet3d_get_hero_option( 'btn1_url', '#lookbook' ) ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="hero_btn2_text"><?php _e( 'Knop 2 - Tekst', 'vet3d' ); ?></label></th>
                            <td><input type="text" id="hero_btn2_text" name="hero_btn2_text" value="<?php echo esc_attr( vet3d_get_hero_option( 'btn2_text', 'Vraag jouw print aan' ) ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="hero_btn2_url"><?php _e( 'Knop 2 - URL', 'vet3d' ); ?></label></th>
                            <td><input type="url" id="hero_btn2_url" name="hero_btn2_url" value="<?php echo esc_url( vet3d_get_hero_option( 'btn2_url', '/offerte/' ) ); ?>" class="regular-text"></td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" name="vet3d_hero_submit" class="button button-primary" value="<?php _e( 'Opslaan', 'vet3d' ); ?>">
                    </p>
                </form>
            </div>
            <?php
        }
    );
    
    add_theme_page(
        __( 'CTA Banner', 'vet3d' ),
        __( 'CTA Banner', 'vet3d' ),
        'manage_options',
        'vet3d-cta-settings',
        function () {
            if ( isset( $_POST['vet3d_cta_submit'] ) && check_admin_referer( 'vet3d_cta_settings' ) ) {
                update_option( 'vet3d_cta_title', sanitize_text_field( $_POST['cta_title'] ) );
                update_option( 'vet3d_cta_subtitle', sanitize_textarea_field( $_POST['cta_subtitle'] ) );
                update_option( 'vet3d_cta_btn_text', sanitize_text_field( $_POST['cta_btn_text'] ) );
                update_option( 'vet3d_cta_btn_url', esc_url_raw( $_POST['cta_btn_url'] ) );
                echo '<div class="notice notice-success"><p>' . __( 'Instellingen opgeslagen!', 'vet3d' ) . '</p></div>';
            }
            ?>
            <div class="wrap">
                <h1><?php _e( 'CTA Banner Instellingen', 'vet3d' ); ?></h1>
                <form method="post">
                    <?php wp_nonce_field( 'vet3d_cta_settings' ); ?>
                    <table class="form-table">
                        <tr>
                            <th><label for="cta_title"><?php _e( 'Titel', 'vet3d' ); ?></label></th>
                            <td><input type="text" id="cta_title" name="cta_title" value="<?php echo esc_attr( get_option( 'vet3d_cta_title', 'Eigen ontwerp in je hoofd?' ) ); ?>" class="large-text"></td>
                        </tr>
                        <tr>
                            <th><label for="cta_subtitle"><?php _e( 'Ondertitel', 'vet3d' ); ?></label></th>
                            <td><textarea id="cta_subtitle" name="cta_subtitle" rows="3" class="large-text"><?php echo esc_textarea( get_option( 'vet3d_cta_subtitle', 'Upload je STL- of OBJ-bestand en krijg direct een prijs.' ) ); ?></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="cta_btn_text"><?php _e( 'Knop tekst', 'vet3d' ); ?></label></th>
                            <td><input type="text" id="cta_btn_text" name="cta_btn_text" value="<?php echo esc_attr( get_option( 'vet3d_cta_btn_text', 'Vraag jouw print aan' ) ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="cta_btn_url"><?php _e( 'Knop URL', 'vet3d' ); ?></label></th>
                            <td><input type="url" id="cta_btn_url" name="cta_btn_url" value="<?php echo esc_url( get_option( 'vet3d_cta_btn_url', '/offerte/' ) ); ?>" class="regular-text"></td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" name="vet3d_cta_submit" class="button button-primary" value="<?php _e( 'Opslaan', 'vet3d' ); ?>">
                    </p>
                </form>
            </div>
            <?php
        }
    );
    
    add_theme_page(
        __( 'USP\'s', 'vet3d' ),
        __( 'USP\'s', 'vet3d' ),
        'manage_options',
        'vet3d-usp-settings',
        function () {
            if ( isset( $_POST['vet3d_usp_submit'] ) && check_admin_referer( 'vet3d_usp_settings' ) ) {
                $usps = array();
                for ( $i = 1; $i <= 4; $i++ ) {
                    if ( ! empty( $_POST['usp_' . $i] ) ) {
                        $usps[] = sanitize_text_field( $_POST['usp_' . $i] );
                    }
                }
                update_option( 'vet3d_usps', $usps );
                echo '<div class="notice notice-success"><p>' . __( 'Instellingen opgeslagen!', 'vet3d' ) . '</p></div>';
            }
            
            $usps = get_option( 'vet3d_usps', array( 'Snelle levering', 'Volledig op maat', 'Upload je ontwerp', 'Veilig betalen' ) );
            while ( count( $usps ) < 4 ) {
                $usps[] = '';
            }
            ?>
            <div class="wrap">
                <h1><?php _e( 'USP\'s Instellingen', 'vet3d' ); ?></h1>
                <p><?php _e( 'Bewerk de USP\'s die in de balk onder de header worden getoond.', 'vet3d' ); ?></p>
                <form method="post">
                    <?php wp_nonce_field( 'vet3d_usp_settings' ); ?>
                    <table class="form-table">
                        <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
                        <tr>
                            <th><label for="usp_<?php echo $i; ?>"><?php printf( __( 'USP %d', 'vet3d' ), $i ); ?></label></th>
                            <td><input type="text" id="usp_<?php echo $i; ?>" name="usp_<?php echo $i; ?>" value="<?php echo esc_attr( $usps[ $i - 1 ] ); ?>" class="regular-text"></td>
                        </tr>
                        <?php endfor; ?>
                    </table>
                    <p class="submit">
                        <input type="submit" name="vet3d_usp_submit" class="button button-primary" value="<?php _e( 'Opslaan', 'vet3d' ); ?>">
                    </p>
                </form>
            </div>
            <?php
        }
    );
} );

// Custom Post Type voor SEO Landing Pages
add_action( 'init', function () {
    register_post_type( 'seo_page', array(
        'labels' => array(
            'name' => __( 'SEO Pagina\'s', 'vet3d' ),
            'singular_name' => __( 'SEO Pagina', 'vet3d' ),
            'add_new' => __( 'Nieuwe toevoegen', 'vet3d' ),
            'add_new_item' => __( 'Nieuwe SEO pagina toevoegen', 'vet3d' ),
            'edit_item' => __( 'Bewerk SEO pagina', 'vet3d' ),
            'new_item' => __( 'Nieuwe SEO pagina', 'vet3d' ),
            'view_item' => __( 'Bekijk SEO pagina', 'vet3d' ),
            'search_items' => __( 'Zoek SEO pagina\'s', 'vet3d' ),
            'not_found' => __( 'Geen SEO pagina\'s gevonden', 'vet3d' ),
        ),
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-search',
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest' => true,
        'rewrite' => array( 'slug' => 'info' ),
    ) );
} );

// Meta box voor SEO pagina instellingen
add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'seo_page_settings',
        __( 'SEO Pagina Instellingen', 'vet3d' ),
        function ( $post ) {
            wp_nonce_field( 'seo_page_meta', 'seo_page_meta_nonce' );
            
            $linked_categories = get_post_meta( $post->ID, '_seo_linked_categories', true );
            $linked_categories = $linked_categories ? $linked_categories : array();
            
            $show_products = get_post_meta( $post->ID, '_seo_show_products', true );
            $products_title = get_post_meta( $post->ID, '_seo_products_title', true );
            $products_per_page = get_post_meta( $post->ID, '_seo_products_per_page', true );
            $products_per_page = $products_per_page ? $products_per_page : 12;
            
            // Haal alle product categorieën op
            $categories = get_terms( array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
            ) );
            ?>
            <div style="margin-bottom: 20px;">
                <h4><?php _e( 'Gekoppelde Product Categorieën', 'vet3d' ); ?></h4>
                <p class="description">
                    <?php _e( 'Selecteer welke productcategorieën getoond moeten worden op deze pagina.', 'vet3d' ); ?>
                </p>
                <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #fafafa;">
                    <?php foreach ( $categories as $category ) : ?>
                        <label style="display: block; margin: 5px 0;">
                            <input 
                                type="checkbox" 
                                name="seo_linked_categories[]" 
                                value="<?php echo esc_attr( $category->term_id ); ?>"
                                <?php checked( in_array( $category->term_id, $linked_categories ) ); ?>
                            >
                            <?php echo esc_html( $category->name ); ?>
                            <span style="color: #999;">(<?php echo $category->count; ?> producten)</span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label>
                    <input type="checkbox" name="seo_show_products" value="1" <?php checked( $show_products, '1' ); ?>>
                    <strong><?php _e( 'Toon producten op deze pagina', 'vet3d' ); ?></strong>
                </label>
                <p class="description">
                    <?php _e( 'Als dit aangevinkt is, worden producten uit de geselecteerde categorieën getoond.', 'vet3d' ); ?>
                </p>
            </div>
            
            <p>
                <label><strong><?php _e( 'Producten sectie titel:', 'vet3d' ); ?></strong></label><br>
                <input 
                    type="text" 
                    name="seo_products_title" 
                    value="<?php echo esc_attr( $products_title ); ?>" 
                    placeholder="<?php _e( 'Bijv: Onze 3D Print Producten', 'vet3d' ); ?>"
                    style="width: 100%;"
                >
            </p>
            
            <p>
                <label><strong><?php _e( 'Aantal producten per pagina:', 'vet3d' ); ?></strong></label><br>
                <input 
                    type="number" 
                    name="seo_products_per_page" 
                    value="<?php echo esc_attr( $products_per_page ); ?>" 
                    min="1" 
                    max="50"
                    style="width: 100px;"
                >
            </p>
            
            <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 10px; margin-top: 15px;">
                <strong>💡 Tip:</strong> Gebruik de content editor hierboven voor SEO-tekst, USP's en uitleg. 
                De producten worden automatisch onder de content getoond.
            </div>
            <?php
        },
        'seo_page',
        'normal',
        'high'
    );
}, 10 );

add_action( 'save_post_seo_page', function ( $post_id ) {
    if ( ! isset( $_POST['seo_page_meta_nonce'] ) || ! wp_verify_nonce( $_POST['seo_page_meta_nonce'], 'seo_page_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Save linked categories
    if ( isset( $_POST['seo_linked_categories'] ) ) {
        $categories = array_map( 'intval', $_POST['seo_linked_categories'] );
        update_post_meta( $post_id, '_seo_linked_categories', $categories );
    } else {
        delete_post_meta( $post_id, '_seo_linked_categories' );
    }
    
    // Save show products setting
    if ( isset( $_POST['seo_show_products'] ) ) {
        update_post_meta( $post_id, '_seo_show_products', '1' );
    } else {
        delete_post_meta( $post_id, '_seo_show_products' );
    }
    
    // Save products title
    if ( isset( $_POST['seo_products_title'] ) ) {
        update_post_meta( $post_id, '_seo_products_title', sanitize_text_field( $_POST['seo_products_title'] ) );
    }
    
    // Save products per page
    if ( isset( $_POST['seo_products_per_page'] ) ) {
        update_post_meta( $post_id, '_seo_products_per_page', intval( $_POST['seo_products_per_page'] ) );
    }
} );

// Shortcode om producten van specifieke categorieën te tonen
add_shortcode( 'vet3d_products', function ( $atts ) {
    if ( ! vet3d_wc_active() ) {
        return '';
    }

    $atts = shortcode_atts( array(
        'category' => '', // Categorie slug of ID (komma-gescheiden voor meerdere)
        'limit' => 12,
        'columns' => 4,
        'orderby' => 'date',
        'order' => 'DESC',
        'title' => '',
    ), $atts );
    
    if ( empty( $atts['category'] ) ) {
        return '<p style="color:#999;">' . __( 'Geen categorie opgegeven.', 'vet3d' ) . '</p>';
    }
    
    // Parse categorieën (kan komma-gescheiden zijn)
    $categories = array_map( 'trim', explode( ',', $atts['category'] ) );
    
    // Bepaal of het IDs of slugs zijn
    $field = is_numeric( $categories[0] ) ? 'term_id' : 'slug';
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => intval( $atts['limit'] ),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => $field,
                'terms' => $categories,
                'operator' => 'IN',
            ),
        ),
    );
    
    $products_query = new WP_Query( $args );
    
    if ( ! $products_query->have_posts() ) {
        return '<p style="color:#999;">' . __( 'Geen producten gevonden.', 'vet3d' ) . '</p>';
    }
    
    ob_start();
    
    if ( ! empty( $atts['title'] ) ) {
        echo '<h2 class="wp-block-heading has-x-large-font-size" style="margin-top:40px;margin-bottom:30px;">' . esc_html( $atts['title'] ) . '</h2>';
    }
    
    echo '<ul class="products columns-' . esc_attr( $atts['columns'] ) . '">';
    
    while ( $products_query->have_posts() ) {
        $products_query->the_post();
        wc_get_template_part( 'content', 'product' );
    }
    
    echo '</ul>';
    
    wp_reset_postdata();
    
    return ob_get_clean();
} );

// Admin notice voor nieuwe SEO pagina's
add_action( 'admin_notices', function () {
    $screen = get_current_screen();
    if ( $screen->post_type === 'seo_page' && $screen->base === 'post' ) {
        global $post;
        if ( $post && $post->post_status === 'publish' ) {
            $linked_categories = get_post_meta( $post->ID, '_seo_linked_categories', true );
            if ( empty( $linked_categories ) ) {
                echo '<div class="notice notice-warning"><p>';
                echo '<strong>' . __( 'Let op:', 'vet3d' ) . '</strong> ';
                echo __( 'Er zijn nog geen productcategorieën gekoppeld aan deze pagina. Scroll naar beneden naar "SEO Pagina Instellingen" om categorieën te selecteren.', 'vet3d' );
                echo '</p></div>';
            }
        }
    }
} );

// WooCommerce - STL upload veld voor producten
add_action( 'woocommerce_product_options_general_product_data', function () {
    global $post;
    
    echo '<div class="options_group">';
    
    // STL file upload
    $stl_file = get_post_meta( $post->ID, '_product_stl_file', true );
    ?>
    <p class="form-field">
        <label><?php _e( '3D Model', 'vet3d' ); ?></label>
        <input type="text" id="product_stl_file" name="product_stl_file" value="<?php echo esc_attr( $stl_file ); ?>" style="width: 70%;" placeholder="<?php _e( 'STL, 3MF of OBJ bestand URL', 'vet3d' ); ?>" />
        <button type="button" class="button upload_stl_button" style="margin-left: 5px;"><?php _e( 'Upload', 'vet3d' ); ?></button>
        <span class="description" style="display: block; margin-top: 5px;">
            <?php _e( 'Upload een 3D bestand (STL, 3MF of OBJ) om 3D preview te tonen bij hover in de winkel.', 'vet3d' ); ?>
        </span>
    </p>
    <?php
    
    echo '</div>';
} );

add_action( 'woocommerce_process_product_meta', function ( $post_id ) {
    if ( isset( $_POST['product_stl_file'] ) ) {
        update_post_meta( $post_id, '_product_stl_file', esc_url_raw( $_POST['product_stl_file'] ) );
    }
} );

// STL upload in admin met media library
add_action( 'admin_footer', function () {
    if ( get_current_screen()->id !== 'product' ) {
        return;
    }
    ?>
    <script>
    jQuery(function($) {
        var stlUploader;
        
        $('.upload_stl_button').on('click', function(e) {
            e.preventDefault();
            
            if (stlUploader) {
                stlUploader.open();
                return;
            }
            
            stlUploader = wp.media({
                title: '<?php _e( 'Kies 3D Model', 'vet3d' ); ?>',
                button: {
                    text: '<?php _e( 'Gebruik dit bestand', 'vet3d' ); ?>'
                },
                library: {
                    type: ['model/stl', 'model/3mf', 'model/obj', 'application/sla', 'application/octet-stream']
                },
                multiple: false
            });
            
            stlUploader.on('select', function() {
                var attachment = stlUploader.state().get('selection').first().toJSON();
                $('#product_stl_file').val(attachment.url);
            });
            
            stlUploader.open();
        });
    });
    </script>
    <?php
} );

// Voeg STL mimetype toe aan toegestane uploads
add_filter( 'upload_mimes', function ( $mimes ) {
    $mimes['stl'] = 'model/stl';
    $mimes['obj'] = 'model/obj';
    $mimes['3mf'] = 'model/3mf';
    return $mimes;
} );

// Extra check voor STL en 3MF bestanden (WordPress blokkeert soms binary files)
add_filter( 'wp_check_filetype_and_ext', function ( $data, $file, $filename, $mimes ) {
    $ext = pathinfo( $filename, PATHINFO_EXTENSION );
    
    if ( $ext === 'stl' ) {
        $data['ext'] = 'stl';
        $data['type'] = 'model/stl';
    }
    
    if ( $ext === '3mf' ) {
        $data['ext'] = '3mf';
        $data['type'] = 'model/3mf';
    }
    
    if ( $ext === 'obj' ) {
        $data['ext'] = 'obj';
        $data['type'] = 'model/obj';
    }
    
    return $data;
}, 10, 4 );

// Sta toe dat STL, 3MF en OBJ bestanden geüpload kunnen worden (security bypass voor deze types)
add_filter( 'wp_handle_upload_prefilter', function ( $file ) {
    $ext = pathinfo( $file['name'], PATHINFO_EXTENSION );
    
    if ( in_array( strtolower( $ext ), array( 'stl', '3mf', 'obj' ) ) ) {
        // Voeg correct mimetype toe
        $mime_types = array(
            'stl' => 'model/stl',
            '3mf' => 'model/3mf',
            'obj' => 'model/obj',
        );
        
        $file['type'] = $mime_types[ strtolower( $ext ) ];
    }
    
    return $file;
} );

// Voeg data-attribute toe aan product loop items
add_action( 'woocommerce_before_shop_loop_item', function () {
    global $product;
    $stl_url = get_post_meta( $product->get_id(), '_product_stl_file', true );
    if ( $stl_url ) {
        echo '<div data-stl-url="' . esc_url( $stl_url ) . '" class="vet-product-with-3d">';
    }
}, 5 );

add_action( 'woocommerce_after_shop_loop_item', function () {
    global $product;
    $stl_url = get_post_meta( $product->get_id(), '_product_stl_file', true );
    if ( $stl_url ) {
        echo '</div>';
    }
}, 15 );
