<?php
/**
 * Title: Scroll-video
 * Slug: vet3d/scroll-video
 * Categories: vet3d
 * Inserter: true
 */

$video_dir = get_template_directory_uri() . '/assets/video/';

// Zelfde teksten als de hero (instelbaar via de hero-instellingen)
$pill = get_option( 'vet3d_hero_pill', '3D-printen op maat' );
$title = get_option( 'vet3d_hero_title', 'Vette prints, precies zoals jij ze wilt.' );
$subtitle = get_option( 'vet3d_hero_subtitle', 'Kant-en-klare ontwerpen of jouw eigen STL-bestand. Wij printen het scherp, snel en in elke kleur.' );
$btn1_text = get_option( 'vet3d_hero_btn1_text', 'Bekijk het lookbook' );
$btn1_url = get_option( 'vet3d_hero_btn1_url', '#lookbook' );
$btn2_text = get_option( 'vet3d_hero_btn2_text', 'Vraag jouw print aan' );
$btn2_url = get_option( 'vet3d_hero_btn2_url', '/offerte/' );
?>
<!-- wp:html -->
<section class="vet-scrollvideo">
<div class="stick">
<video muted playsinline preload="none" aria-hidden="true" poster="<?php echo esc_url( $video_dir . 'scroll-video-poster.jpg' ); ?>" data-src="<?php echo esc_url( $video_dir . 'scroll-video.mp4' ); ?>"></video>
<div class="vet-scrollvideo__content">
<p class="vet-pill"><?php echo esc_html( $pill ); ?></p>
<h1 class="wp-block-heading has-xx-large-font-size"><?php echo esc_html( $title ); ?></h1>
<p class="vet-scrollvideo__sub"><?php echo esc_html( $subtitle ); ?></p>
<div class="wp-block-buttons">
<div class="wp-block-button"><a class="wp-block-button__link has-base-color has-primary-background-color has-text-color has-background wp-element-button" href="<?php echo esc_url( $btn1_url ); ?>"><?php echo esc_html( $btn1_text ); ?></a></div>
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-base-color has-text-color wp-element-button" style="border-width:2px" href="<?php echo esc_url( $btn2_url ); ?>"><?php echo esc_html( $btn2_text ); ?></a></div>
</div>
</div>
</div>
</section>
<!-- /wp:html -->
