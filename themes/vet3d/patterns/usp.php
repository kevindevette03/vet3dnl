<?php
/**
 * Title: USP-balk
 * Slug: vet3d/usp
 * Categories: vet3d
 * Inserter: true
 */

$usps = get_option( 'vet3d_usps', array(
    'Snelle levering',
    'Volledig op maat',
    'Upload je ontwerp',
    'Veilig betalen'
) );
?>
<!-- wp:html -->
<div class="vet-usp"><div class="row">
<?php foreach ( $usps as $usp ) : ?>
<span><span class="ic">&#10003;</span><?php echo esc_html( $usp ); ?></span>
<?php endforeach; ?>
</div></div>
<!-- /wp:html -->
