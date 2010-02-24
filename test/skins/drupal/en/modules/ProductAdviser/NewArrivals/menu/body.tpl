{* New Arrivals menu body *}
<ol>
  <li FOREACH="newArrivalsProducts,id,product">
    <a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={product.category.category_id}" class="SidebarItems">{product.name:h}</a>
  </li>
</ol>
<div IF="additionalPresent">
    <a href="cart.php?target=NewArrivals" onClick="this.blur()">All new arrivals...</a>
</div>

