{* Recently viewed menu body *}
<div FOREACH="recentliesProducts,id,product">
  <form action="{buildURLPath(#cart#,#add#,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" method="GET" name="add_to_cart_rv_{product.product_id}" class="product-block">
    <input FOREACH="buildURLArguments(#cart#,#add#,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id)),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />

    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^RP.product.product_id,#category_id#^RP.product.category.category_id))}" IF="product.hasImage()"><img src="{product.imageURL}" width="50" alt="" /></a>
    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}" class="SidebarItems">{product.name:h}</a>
    <br />
    <widget class="XLite_View_Price" product="{product}" template="common/price_plain.tpl" />
    <widget template="buy_now.tpl" product="{product}" />
  </form>
  <br /><br />
</div>

<div IF="additionalPresent">
  <a href="{buildURL(#recently_viewed#)}" onClick="this.blur()">All viewed...</a>
</div>
