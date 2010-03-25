{* SVN $Id$ *}
<ul class="list-body list-body-grid">
  <li class="item">
    <span class="draggable-mark">Drag me to the cart</span>
    <a IF="product.hasThumbnail()" class="product-thumbnail" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}"><img src="{product.getThumbnailURL()}" alt="{product.name}" /></a>
    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" class="product-name">{product.name:h}</a>
    <widget class="XLite_View_Price" product="{product}" displayOnlyPrice="true" />
    <widget class="XLite_View_BuyNow" product="{product}" style="aux-button add-to-cart" />
  </li>
</ul>
