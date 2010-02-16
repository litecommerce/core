{* SVN $Id$ *}
<div class="quantity">
  <strong>Quantity:</strong>
  <span IF="{product.inventory.amount}">{product.inventory.amount} item(s) available</span>
  <span IF="{!product.inventory.amount}">- out of stock -</span>
</div>

<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/product_quantity.tpl" visible="{xlite.PA_InventorySupport}">
