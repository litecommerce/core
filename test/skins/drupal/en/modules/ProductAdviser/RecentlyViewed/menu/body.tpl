{* Recently viewed menu body *}
<ol>
  <li FOREACH="recentliesProducts,id,product">
    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}" class="SidebarItems">{product.name:h}</a>
  </li>
</ol>

<div IF="additionalPresent">
  <a href="{buildURL(#recently_viewed#)}" onClick="this.blur()">All viewed...</a>
</div>
