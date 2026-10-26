<?php
/**
 * Title: Custom upload-banner
 * Slug: vet3d/custom-cta
 * Categories: vet3d
 * Inserter: true
 */

$cta_title = get_option( 'vet3d_cta_title', 'Eigen ontwerp in je hoofd?' );
$cta_subtitle = get_option( 'vet3d_cta_subtitle', 'Upload je STL- of OBJ-bestand en krijg direct een prijs.' );
$cta_btn_text = get_option( 'vet3d_cta_btn_text', 'Vraag jouw print aan' );
$cta_btn_url = get_option( 'vet3d_cta_btn_url', '/offerte/' );
?>
<!-- wp:group {"anchor":"opmaat","backgroundColor":"primary","style":{"spacing":{"padding":{"top":"38px","bottom":"38px","left":"28px","right":"28px"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div id="opmaat" class="wp-block-group has-primary-background-color has-background" style="padding-top:38px;padding-right:28px;padding-bottom:38px;padding-left:28px">
<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"textColor":"base","fontSize":"x-large"} -->
<h2 class="wp-block-heading has-base-color has-text-color has-x-large-font-size"><?php echo esc_html( $cta_title ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"color":{"text":"#5A2410"},"typography":{"fontSize":"15px"}}} -->
<p class="has-text-color" style="color:#5A2410;font-size:15px"><?php echo esc_html( $cta_subtitle ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"contrast","textColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-base-color has-contrast-background-color has-text-color has-background wp-element-button" href="<?php echo esc_url( $cta_btn_url ); ?>"><?php echo esc_html( $cta_btn_text ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
