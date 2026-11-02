<?php
/**
 * Title: Scroll-video
 * Slug: vet3d/scroll-video
 * Categories: vet3d
 * Inserter: true
 */

$video_dir = get_template_directory_uri() . '/assets/video/';
// Themaversie als cache-buster, zodat een nieuwe video direct zichtbaar is
$video_ver = '?ver=' . wp_get_theme()->get( 'Version' );

?>
<!-- wp:html -->
<section class="vet-scrollvideo" aria-label="Video">
<div class="stick">
<video muted playsinline preload="none" aria-hidden="true" poster="<?php echo esc_url( $video_dir . 'scroll-video-poster.jpg' . $video_ver ); ?>" data-src="<?php echo esc_url( $video_dir . 'scroll-video.mp4' . $video_ver ); ?>"></video>
</div>
</section>
<!-- /wp:html -->
