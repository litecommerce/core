{* SVN $Id$ *}
<ul class="list-body list-body-grid">

  <li class="item item-center" style="width: 100%">

    <div>    
      <a class="product-thumbnail-center" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id))}" IF="product.hasThumbnail()"><img src="{product.getThumbnailURL()}" alt="" /></a>

      <a class="product-name" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id))}">{product.name}</a>

      <widget class="XLite_View_Price" product="{product}" displayOnlyPrice="true" />

      <widget class="XLite_View_BuyNow" product="{product}" style="aux-button add-to-cart" />

    </div>

  </li>

</ul>
