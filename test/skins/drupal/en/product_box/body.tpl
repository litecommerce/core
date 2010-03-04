{* SVN $Id$ *}
<ul class="list-body list-body-grid">

  <li class="item item-center" style="width: 100%">

    <div>    
      <a class="product-thumbnail-center" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id))}" IF="product.hasImage()"><img src="{product.imageURL}" alt="" /></a>

      <a class="product-name" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id))}">{product.name}</a>

      <widget class="XLite_View_Price" product="{product}" displayOnlyPrice="true" />

      <widget template="buy_now.tpl" product="{product}" />

    </div>

  </li>

</ul>
