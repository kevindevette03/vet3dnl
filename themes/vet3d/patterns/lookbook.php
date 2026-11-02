<?php
/**
 * Title: Lookbook
 * Slug: vet3d/lookbook
 * Categories: vet3d
 * Inserter: true
 */

$lookbook_items = get_posts( array(
    'post_type' => 'lookbook',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
) );
?>
<!-- wp:html -->
<section id="lookbook" class="vet-log alignfull">
<header class="vet-log__head">
<div>
<p class="vet-log__mono">Printlog &mdash; <?php echo esc_html( sprintf( '%02d', count( $lookbook_items ) ) ); ?> projecten</p>
<h2>Dit maakten we al</h2>
</div>
<div class="vet-log__nav" hidden>
<button type="button" class="vet-log__arrow" data-dir="-1" aria-label="Vorige projecten">&larr;</button>
<button type="button" class="vet-log__arrow" data-dir="1" aria-label="Volgende projecten">&rarr;</button>
</div>
</header>
<div class="vet-log__strip">
<?php
foreach ( $lookbook_items as $index => $item ) :
    $story = get_post_meta( $item->ID, '_lookbook_story', true );
    $material = get_post_meta( $item->ID, '_lookbook_material', true );
    $print_time = get_post_meta( $item->ID, '_lookbook_time', true );
    $stl_url = get_post_meta( $item->ID, '_lookbook_stl_file', true );
    $thumbnail_id = get_post_thumbnail_id( $item );
    $image = $thumbnail_id ? wp_get_attachment_image_src( $thumbnail_id, 'large' ) : false;

    // De foto wordt niet bijgesneden: alle foto's zijn even hoog, de vorm bepaalt de breedte
    $ratio = $image ? round( $image[1] / max( 1, $image[2] ), 3 ) : 0.75;
    ?>
<article class="vet-log__entry" style="--vet-ratio:<?php echo esc_attr( $ratio ); ?>">
<?php if ( $image ) : ?>
<figure class="vet-log__photo"<?php echo $stl_url ? ' data-stl-url="' . esc_url( $stl_url ) . '"' : ''; ?>>
<?php echo wp_get_attachment_image( $thumbnail_id, 'large', false, array( 'alt' => get_the_title( $item ), 'sizes' => '(max-width: 781px) 80vw, 500px' ) ); ?>
</figure>
<?php endif; ?>
<div class="vet-log__info">
<p class="vet-log__mono">#<?php echo esc_html( sprintf( '%03d', $index + 1 ) ); ?></p>
<h3><?php echo esc_html( get_the_title( $item ) ); ?></h3>
<?php if ( $story ) : ?>
<p class="vet-log__story"><?php echo esc_html( $story ); ?></p>
<?php endif; ?>
<?php if ( $material || $print_time ) : ?>
<dl class="vet-log__specs">
<?php if ( $material ) : ?>
<div><dt>Materiaal</dt><dd><?php echo esc_html( $material ); ?></dd></div>
<?php endif; ?>
<?php if ( $print_time ) : ?>
<div><dt>Printtijd</dt><dd><?php echo esc_html( $print_time ); ?></dd></div>
<?php endif; ?>
</dl>
<?php endif; ?>
<?php if ( $stl_url && $image ) : ?>
<button type="button" class="vet-log__3d" aria-pressed="false">Bekijk in 3D</button>
<?php endif; ?>
</div>
</article>
<?php endforeach; ?>
<article class="vet-log__next">
<p class="vet-log__mono">#<?php echo esc_html( sprintf( '%03d', count( $lookbook_items ) + 1 ) ); ?> &mdash; volgende in de wachtrij</p>
<div>
<h3>Jouw idee.</h3>
<p>Vertel wat je in gedachten hebt, wij printen het op maat.</p>
<a class="vet-hero__btn is-dark" href="/offerte/">Vraag jouw print aan</a>
</div>
</article>
</div>
</section>
<!-- /wp:html -->
