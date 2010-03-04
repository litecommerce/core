{* Recently viewed menu body *}
<ul class="list-body-sidebar">

  <li class="item" FOREACH="recentliesProducts,id,product">

    <a class="product-thumbnail" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}" IF="product.hasImage()"><img src="{product.imageURL}" width="50" alt="" /></a>

    <div class="body">

        <a class="product-name" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}">{product.name:h}</a>
        <br />

        <widget class="XLite_View_Price" product="{product}" displayOnlyPrice="true" />
        <widget template="buy_now.tpl" product="{product}" />

    </div>

  </li>

  <li IF="additionalPresent">
    <a class="link" href="{buildURL(#recently_viewed#)}" onClick="this.blur()">All viewed...</a>
  </li>

</ul>
