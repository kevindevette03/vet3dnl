<?php
/**
 * Title: Hero
 * Slug: vet3d/hero
 * Categories: vet3d
 * Inserter: true
 */

$pill = get_option( 'vet3d_hero_pill', '3D-printen op maat' );
$title = get_option( 'vet3d_hero_title', 'Vette prints, precies zoals jij ze wilt.' );
$subtitle = get_option( 'vet3d_hero_subtitle', 'Kant-en-klare ontwerpen of jouw eigen STL-bestand. Wij printen het scherp, snel en in elke kleur.' );
$btn1_text = get_option( 'vet3d_hero_btn1_text', 'Bekijk het lookbook' );
$btn1_url = get_option( 'vet3d_hero_btn1_url', '#lookbook' );
$btn2_text = get_option( 'vet3d_hero_btn2_text', 'Vraag jouw print aan' );
$btn2_url = get_option( 'vet3d_hero_btn2_url', '/offerte/' );
?>
<!-- wp:group {"backgroundColor":"paper","style":{"spacing":{"padding":{"top":"54px","bottom":"54px","left":"28px","right":"28px"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group has-paper-background-color has-background" style="padding-top:54px;padding-right:28px;padding-bottom:54px;padding-left:28px">
<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center">
<!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%">
<!-- wp:paragraph {"className":"vet-pill"} -->
<p class="vet-pill"><?php echo esc_html( $pill ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"xx-large"} -->
<h1 class="wp-block-heading has-xx-large-font-size"><?php echo esc_html( $title ); ?></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","style":{"typography":{"fontSize":"16px"}}} -->
<p class="has-muted-color has-text-color" style="font-size:16px"><?php echo esc_html( $subtitle ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"primary","textColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-base-color has-primary-background-color has-text-color has-background wp-element-button" href="<?php echo esc_url( $btn1_url ); ?>"><?php echo esc_html( $btn1_text ); ?></a></div>
<!-- /wp:button -->
<!-- wp:button {"textColor":"contrast","className":"is-style-outline","style":{"border":{"width":"2px"}}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-contrast-color has-text-color wp-element-button" style="border-width:2px" href="<?php echo esc_url( $btn2_url ); ?>"><?php echo esc_html( $btn2_text ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"42%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:42%">
<!-- wp:html -->
<div style="display:flex;justify-content:center"><svg width="220" height="234" viewBox="0 0 180 190" role="img" aria-label="3D-print" xmlns="http://www.w3.org/2000/svg">
<ellipse cx="90" cy="170" rx="68" ry="14" fill="#E7DDCE"/>
<path d="M90 14 L156 52 L90 90 L24 52 Z" fill="#FF8C42"/>
<path d="M24 52 L90 90 L90 158 L24 120 Z" fill="#FF6B35"/>
<path d="M156 52 L90 90 L90 158 L156 120 Z" fill="#D44A1A"/>
<path d="M24 66 L90 104" stroke="#FFB07A" stroke-width="2" fill="none"/>
<path d="M24 80 L90 118" stroke="#FFB07A" stroke-width="2" fill="none"/>
<path d="M24 94 L90 132" stroke="#FFB07A" stroke-width="2" fill="none"/>
<path d="M24 108 L90 146" stroke="#FFB07A" stroke-width="2" fill="none"/>
<path d="M156 66 L90 104" stroke="#B23E14" stroke-width="2" fill="none"/>
<path d="M156 80 L90 118" stroke="#B23E14" stroke-width="2" fill="none"/>
<path d="M156 94 L90 132" stroke="#B23E14" stroke-width="2" fill="none"/>
</svg></div>
<!-- /wp:html -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
