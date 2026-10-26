<?php
/**
 * Title: Populaire producten
 * Slug: vet3d/featured-products
 * Categories: vet3d
 * Inserter: true
 */
?>
<!-- wp:group {"anchor":"producten","style":{"spacing":{"padding":{"top":"48px","bottom":"56px","left":"28px","right":"28px"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div id="producten" class="wp-block-group" style="padding-top:48px;padding-right:28px;padding-bottom:56px;padding-left:28px">
<!-- wp:heading {"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size">Populair</h2>
<!-- /wp:heading -->
<!-- wp:woocommerce/product-collection {"queryId":0,"query":{"perPage":4,"pages":0,"offset":0,"postType":"product","order":"desc","orderBy":"popularity","search":"","exclude":[],"inherit":false,"taxQuery":{},"isProductCollectionBlock":true,"featured":false,"woocommerceOnSale":false,"woocommerceStockStatus":["instock"],"woocommerceAttributes":[],"woocommerceHandPickedProducts":[]},"tagName":"div","displayLayout":{"type":"flex","columns":4,"shrinkColumns":true}} -->
<div class="wp-block-woocommerce-product-collection">
<!-- wp:woocommerce/product-template -->
<!-- wp:woocommerce/product-image {"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} /-->
<!-- wp:post-title {"textAlign":"left","level":3,"isLink":true,"style":{"typography":{"fontSize":"15px","fontWeight":"600"}},"__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->
<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true} /-->
<!-- wp:woocommerce/product-button {"textAlign":"left","isDescendentOfQueryLoop":true} /-->
<!-- /wp:woocommerce/product-template -->
</div>
<!-- /wp:woocommerce/product-collection -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"},"style":{"spacing":{"margin":{"top":"28px"}}}} -->
<div class="wp-block-buttons" style="margin-top:28px">
<!-- wp:button {"backgroundColor":"primary","textColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-base-color has-primary-background-color has-text-color has-background wp-element-button" href="/shop/">Bekijk de hele shop</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
