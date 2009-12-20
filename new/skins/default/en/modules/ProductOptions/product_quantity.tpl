<tbody IF="product.productOptions&product.inventory.found&!product.tracking">
<tr><td width="30%" class="ProductDetails">Quantity:</td><td IF="{product.inventory.amount}" class="ProductDetails" nowrap>{product.inventory.amount} item(s) available</td><td IF="{!product.inventory.amount}" class="ErrorMessage" nowrap>- out of stock -</td></tr>
<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/product_quantity.tpl" visible="{xlite.PA_InventorySupport}">
</tbody>
<tbody IF="product.productOptions&product.tracking&product.outOfStock">
<tr><td width="30%" class="ProductDetails">Quantity:</td><td class="ErrorMessage" nowrap>- out of stock -</td></tr>
</tbody>
