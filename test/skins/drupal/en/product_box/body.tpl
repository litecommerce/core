{* SVN $Id$ *}
<form action="{shopURL(#cart.php#)}" method="GET" name="add_to_cart_{product.product_id}" class="product-block">
  <input type="hidden" name="target" value="cart">
  <input type="hidden" name="action" value="add">
  <input type="hidden" name="product_id" value="{product.product_id}">
  <input type="hidden" name="category_id" value="{category_id}">

  <div class="name">
    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id))}">{product.name}</a>
  </div>

  <div IF="product.hasImage()" class="image">
    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id))}"><img src="{product.imageURL}" alt="" /></a>
  </div>

  <div IF="{product.sku}" class="sku">
    <strong>SKU:</strong>
    <span>{product.sku}</span>
  </div>

  <widget module="InventoryTracking" template="modules/InventoryTracking/product_quantity_box.tpl" IF="!product.productOptions" visible="{product.inventory.found}"/>
  <widget module="ProductOptions" template="modules/ProductOptions/product_quantity_box.tpl">

  <widget class="XLite_View_Price" product="{product}" template="common/price_plain.tpl">

  <widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/product_button_plain.tpl" visible="{!priceNotificationSaved}">

  <br /><br /><widget template="buy_now.tpl" product="{product}" />

</form>

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_NotifyForm">
<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/notify_form.tpl" visible="{!priceNotificationSaved}">
