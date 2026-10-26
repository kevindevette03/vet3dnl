<?php
/**
 * Title: Lookbook
 * Slug: vet3d/lookbook
 * Categories: vet3d
 * Inserter: true
 */

// Haal lookbook items op
$lookbook_items = get_posts( array(
    'post_type' => 'lookbook',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
) );

// Gradient presets voor als er geen afbeelding is
$gradients = array(
    'linear-gradient(135deg,#FF8C42 0%,#D44A1A 100%)',
    'linear-gradient(135deg,#1B1B22 0%,#3A3A46 100%)',
    'linear-gradient(135deg,#22CBD0 0%,#127E82 100%)',
    'linear-gradient(135deg,#FDE3D6 0%,#FF8C42 100%)',
    'linear-gradient(135deg,#3A3A46 0%,#1B1B22 100%)',
    'linear-gradient(135deg,#D44A1A 0%,#8A2E0E 100%)',
    'linear-gradient(135deg,#22CBD0 0%,#1B1B22 100%)',
    'linear-gradient(135deg,#FF8C42 0%,#FF6B35 100%)',
    'linear-gradient(135deg,#1B1B22 0%,#D44A1A 100%)',
);
?>
<!-- wp:group {"anchor":"lookbook","style":{"spacing":{"padding":{"top":"56px","bottom":"64px","left":"28px","right":"28px"}}},"layout":{"type":"constrained","contentSize":"1220px"}} -->
<div id="lookbook" class="wp-block-group" style="padding-top:56px;padding-right:28px;padding-bottom:64px;padding-left:28px">
<!-- wp:paragraph {"className":"vet-pill"} -->
<p class="vet-pill">Lookbook</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-xx-large-font-size">Dit maakten we al</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"muted","style":{"typography":{"fontSize":"16px"},"spacing":{"margin":{"bottom":"6px"}}}} -->
<p class="has-muted-color has-text-color" style="margin-bottom:6px;font-size:16px">Een greep uit ons werk. Alles is op aanvraag te maken — in jouw formaat, kleur en materiaal. Hover over items met 3D-icoon voor interactieve preview.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"vet-lookbook","layout":{"type":"default"}} -->
<div class="wp-block-group vet-lookbook">

<?php
if ( ! empty( $lookbook_items ) ) {
    $counter = 0;
    foreach ( $lookbook_items as $item ) {
        $size_class = get_post_meta( $item->ID, '_lookbook_size', true );
        $dark_text = get_post_meta( $item->ID, '_lookbook_dark_text', true );
        $thumbnail = get_the_post_thumbnail_url( $item->ID, 'large' );
        $stl_url = get_post_meta( $item->ID, '_lookbook_stl_file', true );
        $gradient = $gradients[ $counter % count( $gradients ) ];
        $classes = array( 'vet-tile' );
        if ( $size_class ) {
            $classes[] = $size_class;
        }
        if ( $dark_text ) {
            $classes[] = 'tile-dark-text';
        }
        $class_string = implode( ' ', $classes );
        $stl_attr = $stl_url ? ' data-stl-url="' . esc_url( $stl_url ) . '"' : '';
        
        if ( $thumbnail ) {
            ?>
<!-- wp:cover {"url":"<?php echo esc_url( $thumbnail ); ?>","dimRatio":0,"className":"<?php echo esc_attr( $class_string ); ?>","layout":{"type":"constrained"}} -->
<div class="wp-block-cover <?php echo esc_attr( $class_string ); ?>"<?php echo $stl_attr; ?>><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( $thumbnail ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"textColor":"base","className":"vet-tile-label"} -->
<p class="has-base-color has-text-color vet-tile-label"><?php echo esc_html( get_the_title( $item ) ); ?><?php if ( $stl_url ) echo ' <span style="font-size:0.8em;">🔮</span>'; ?></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover -->
            <?php
        } else {
            ?>
<!-- wp:cover {"customGradient":"<?php echo esc_attr( $gradient ); ?>","dimRatio":0,"isUserOverlayColor":true,"className":"<?php echo esc_attr( $class_string ); ?>","layout":{"type":"constrained"}} -->
<div class="wp-block-cover <?php echo esc_attr( $class_string ); ?>"<?php echo $stl_attr; ?>><span aria-hidden="true" class="wp-block-cover__background has-background-gradient" style="background:<?php echo esc_attr( $gradient ); ?>"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"textColor":"base","className":"vet-tile-label"} -->
<p class="has-base-color has-text-color vet-tile-label"><?php echo esc_html( get_the_title( $item ) ); ?><?php if ( $stl_url ) echo ' <span style="font-size:0.8em;">🔮</span>'; ?></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover -->
            <?php
        }
        $counter++;
    }
} else {
    // Fallback: als er geen items zijn, toon standaard tegels
    ?>
<!-- wp:cover {"customGradient":"linear-gradient(135deg,#FF8C42 0%,#D44A1A 100%)","dimRatio":0,"isUserOverlayColor":true,"className":"vet-tile tile-big","layout":{"type":"constrained"}} -->
<div class="wp-block-cover vet-tile tile-big"><span aria-hidden="true" class="wp-block-cover__background has-background-gradient" style="background:linear-gradient(135deg,#FF8C42 0%,#D44A1A 100%)"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"textColor":"base","className":"vet-tile-label"} -->
<p class="has-base-color has-text-color vet-tile-label">Taarttoppings</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover -->

<!-- wp:cover {"customGradient":"linear-gradient(135deg,#1B1B22 0%,#3A3A46 100%)","dimRatio":0,"isUserOverlayColor":true,"className":"vet-tile tile-tall","layout":{"type":"constrained"}} -->
<div class="wp-block-cover vet-tile tile-tall"><span aria-hidden="true" class="wp-block-cover__background has-background-gradient" style="background:linear-gradient(135deg,#1B1B22 0%,#3A3A46 100%)"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"textColor":"base","className":"vet-tile-label"} -->
<p class="has-base-color has-text-color vet-tile-label">Sleutelhangers</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover -->

<!-- wp:cover {"customGradient":"linear-gradient(135deg,#22CBD0 0%,#127E82 100%)","dimRatio":0,"isUserOverlayColor":true,"className":"vet-tile","layout":{"type":"constrained"}} -->
<div class="wp-block-cover vet-tile"><span aria-hidden="true" class="wp-block-cover__background has-background-gradient" style="background:linear-gradient(135deg,#22CBD0 0%,#127E82 100%)"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"textColor":"base","className":"vet-tile-label"} -->
<p class="has-base-color has-text-color vet-tile-label">Foto naar outline</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover -->

<!-- wp:cover {"customGradient":"linear-gradient(135deg,#FDE3D6 0%,#FF8C42 100%)","dimRatio":0,"isUserOverlayColor":true,"className":"vet-tile tile-dark-text","layout":{"type":"constrained"}} -->
<div class="wp-block-cover vet-tile tile-dark-text"><span aria-hidden="true" class="wp-block-cover__background has-background-gradient" style="background:linear-gradient(135deg,#FDE3D6 0%,#FF8C42 100%)"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"vet-tile-label"} -->
<p class="vet-tile-label">Foto naar beeldje</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover -->
    <?php
}
?>

<!-- wp:group {"className":"vet-tile vet-cta tile-wide","backgroundColor":"primary","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"28px","bottom":"28px","left":"30px","right":"30px"}}}} -->
<div class="wp-block-group vet-tile vet-cta tile-wide has-primary-background-color has-background" style="padding-top:28px;padding-right:30px;padding-bottom:28px;padding-left:30px"><!-- wp:heading {"textColor":"base","fontSize":"x-large"} -->
<h3 class="wp-block-heading has-base-color has-text-color has-x-large-font-size">Alles kan op aanvraag</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"color":{"text":"#5A2410"},"typography":{"fontSize":"15px"}}} -->
<p class="has-text-color" style="color:#5A2410;font-size:15px">Vertel wat je in gedachten hebt, wij printen het op maat.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"14px"}}}} -->
<div class="wp-block-buttons" style="margin-top:14px"><!-- wp:button {"backgroundColor":"contrast","textColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-base-color has-contrast-background-color has-text-color has-background wp-element-button" href="/offerte/">Vraag jouw print aan</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
