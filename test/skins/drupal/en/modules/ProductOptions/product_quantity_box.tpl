{* SVN $Id$ *}
<div IF="product.productOptions&product.inventory.found&!product.tracking" class="quantity">

  <strong>Quantity:</strong>
  <span IF="{product.inventory.amount}">{product.inventory.amount} item(s) available</span>
  <span IF="{!product.inventory.amount}">- out of stock -</span>
</div>

<widget module="ProductAdviser"  class="XLite_Module_ProductAdviser_View_NotifyLink">

<div IF="product.productOptions&product.tracking&product.outOfStock" class="quantity">

  <strong>Quantity:</strong>
  <span>- out of stock -</span>

</div>
