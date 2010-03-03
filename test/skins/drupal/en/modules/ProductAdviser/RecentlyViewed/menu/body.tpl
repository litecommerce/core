{* Recently viewed menu body *}
<div FOREACH="recentliesProducts,id,product">

  <div>
    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}" IF="product.hasImage()"><img src="{product.imageURL}" width="50" alt="" /></a>
  </div>

  <div>

    <div>
      <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}" class="SidebarItems">{product.name:h}</a>
    </div>

    <div>
      <widget class="XLite_View_Price" product="{product}" template="common/price_plain.tpl" />
      <widget template="buy_now.tpl" product="{product}" />
    </div>

  </div>

</div>

<div IF="additionalPresent">
  <a href="{buildURL(#recently_viewed#)}" onClick="this.blur()">All viewed...</a>
</div>
