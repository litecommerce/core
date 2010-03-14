{* SVN $Id$ *}
<div class="delete-from-cart">
  <input type="image" src="images/spacer.gif" />
  {* <widget class="XLite_View_Button_Regular" label="X" action="delete" formParams="{_ARRAY_(#cart_id#^cart_id)}" /> *}
</div>

<div class="item-thumbnail" IF="item.hasThumbnail()">
  <a href="{item.url}"><img src="{item.thumbnailURL}" alt="{item.name}"></a>
</div>

<div class="item-info">
  <div class="item-title"><a href="{item.url}">{item.name}</a></div>
  <div class="item-sku" IF="{item.sku}">SKU: {item.sku}</div>
  <div class="item-weight" IF="{item.weight}">Weight: {item.weight} {config.General.weight_symbol}</div>
  <div class="item-options">
  <widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" visible="{item.hasOptions()}" item="{item}">
  </div>
</div>

<div class="item-actions">

  <div class="item-sums">
    <span class="item-price">{price_format(item,#price#):h}</span>
    <span class="sums-multiply">x</span>
    <span class="item-quantity"><input type="text" name="amount[{cart_id}]" value="{item.amount}" size="3" maxlength="6" /></span>
    <span class="sums-equals">=</span>
    <span class="item-subtotal">{price_format(item,#total#):h}</span>
  </div>

  {*<span IF="!item.valid">(!) This product is out of stock or it has been disabled for sale.</span>*}
  {*<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/cart_item.tpl" visible="{xlite.PA_InventorySupport}">*}
  {*<widget class="XLite_View_Button_Submit" label="Update item" />*}
  {*<widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/item.tpl">*}

  <div class="item-buttons">
    <span class="move-to-wishlist"><a href="#">Move to wishlist</a></span>
  </div>

</div>
