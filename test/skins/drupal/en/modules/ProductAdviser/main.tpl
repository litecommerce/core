<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_RelatedProducts" template="common/dialog.tpl" />
<widget module="ProductAdviser" target="product" template="common/dialog.tpl" head="People who buy this product also buy" body="{config.ProductAdviser.pab_template}" visible="{config.ProductAdviser.products_also_buy_enabled=#Y#&product.ProductsAlsoBuy&!page}" />
<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_NewArrivals" template="common/dialog.tpl" displayMode="dialog" />
