{* New Arrivals menu body *}
<ol>
  <li FOREACH="getNewArrivalsProducts(),id,product">
    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}" class="SidebarItems">{product.name:h}</a>
  </li>
</ol>
{* FIXME - this condition is never evaluates to "true" *}
<div IF="additionalPresent">
    <a href="{buildURL(#NewArrivals#,##)}" onClick="this.blur()">All new arrivals...</a>
</div>

