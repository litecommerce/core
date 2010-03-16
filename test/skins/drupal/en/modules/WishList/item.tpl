{* SVN $Id$ *}
<td class="delete-from-wishlist">
  <widget class="XLite_View_Button_Image" label="Remove" action="delete" formParams="{_ARRAY_(#item_id#^item.item_id,#wishlist_id#^item.wishlist_id,#product_id#^item.product_id)}" />
</td>

<td class="item-thumbnail" IF="item.hasImage()">
  <a href="{buildURL(item.url.target,item.url.action,item.url.arguments)}"><img src="{item.imageURL}"alt="{item.name}"></a>
</td>

<td class="item-info">
  <p class="item-title"><a href="{buildURL(item.url.target,item.url.action,item.url.arguments)}">{item.name:h}</a></p>
  <p class="item-sku" IF="{item.sku}">SKU: {item.sku}</p>
  <p class="item-weight" IF="{item.weight}">Weight: {item.weight} {config.General.weight_symbol}</p>
  <p class="item-options">
    <widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" visible="{item.hasOptions()}" item="{item}" />
  </p>
</td>

<td class="item-actions">

  <form action="{buildURL(#wishlist#,#update#,_ARRAY_(#item_id#^item.item_id,#wishlist_id#^item.wishlist_id,#product_id#^item.product_id))}" method="POST" name="update{key}_form">
    <input FOREACH="buildURLArguments(#wishlist#,#update#,_ARRAY_(#item_id#^item.item_id,#wishlist_id#^item.wishlist_id,#product_id#^item.product_id)),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />

    <div class="item-sums">
      <span class="item-price">{price_format(item,#price#):h}</span>
      <span class="sums-multiply">x</span>
      <span class="item-quantity"><input type="text" name="wishlist_amount" value="{item.amount}" size="3" maxlength="6" /></span>
      <span class="sums-equals">=</span>
      <span class="item-subtotal">{price_format(item,#total#):h}</span>
    </div>

    {* <widget class="XLite_View_Button_Submit" label="Update amount" /> *}
  
    <div class="item-buttons">
      <widget class="XLite_View_Button_Regular" style="aux-button add-to-cart" label="Add to cart" action="add" formParams="{_ARRAY_(#target#^#cart#,#item_id#^item.item_id,#wishlist_id#^item.wishlist_id,#product_id#^item.product_id)}" />
    </div>

  </form>

</td>
