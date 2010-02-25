<widget module="ProductAdviser" target="product" template="common/dialog.tpl" head="Related products" body="{config.ProductAdviser.rp_template}" visible="{config.ProductAdviser.related_products_enabled=#Y#&product.RelatedProducts&!page}">
<widget module="ProductAdviser" target="product" template="common/dialog.tpl" head="People who buy this product also buy" body="{config.ProductAdviser.pab_template}" visible="{config.ProductAdviser.products_also_buy_enabled=#Y#&product.ProductsAlsoBuy&!page}">
<widget module="ProductAdviser" target="RecentlyViewed" template="common/dialog.tpl" head="Recently Viewed" body="modules/ProductAdviser/recently_viewed.tpl">

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_NewArrivals" displayMode="dialog" />
