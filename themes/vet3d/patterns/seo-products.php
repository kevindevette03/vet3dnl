<?php
/**
 * Title: SEO Pagina - Product Sectie
 * Slug: vet3d/seo-products
 * Categories: vet3d
 * Inserter: true
 * Description: Toon producten uit specifieke categorieën op een SEO pagina
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:40px;padding-bottom:40px">
<!-- wp:heading {"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size">Onze Producten</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color">Bekijk hieronder ons volledige assortiment.</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[vet3d_products category="YOUR-CATEGORY-SLUG" limit="12" columns="4" title=""]
<!-- /wp:shortcode -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"13px"}},"textColor":"muted"} -->
<p class="has-muted-color has-text-color" style="font-size:13px"><em>💡 Tip: Vervang "YOUR-CATEGORY-SLUG" met de slug van je productcategorie. Gebruik komma's voor meerdere categorieën.</em></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
