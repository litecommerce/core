<widget template="modules/InventoryTracking/tracking_selection.tpl" visible="{product.productOptions}">
<widget template="modules/InventoryTracking/inventory_tracking.tpl" visible="{!product.tracking}">
<widget module="ProductOptions" template="modules/ProductOptions/inventory_tracking.tpl" visible="{product.tracking}">
