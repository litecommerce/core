<?php
    $find_str = <<<EOT
        <tr IF="{product.sku}"><td width="30%" class="ProductDetails">SKU:</td><td class="ProductDetails" nowrap>{product.sku}</td></tr>
        
        <widget module="InventoryTracking" template="modules/InventoryTracking/product_quantity.tpl" IF="!product.productOptions" visible="{product.inventory.found}"/>

        <widget class="CExtraFields" product="{product}">
        <tr IF="{!product.weight=0}"><td width="30%" class="ProductDetails">Weight:</td><td class="ProductDetails" nowrap>{product.weight} {config.General.weight_symbol}</td></tr>
        <tr><td width="30%" class="ProductPriceTitle">Price:</td><td class="ProductPrice" nowrap>{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></td></tr>

        <widget module="ProductOptions" template="modules/ProductOptions/product_options.tpl" IF="product.hasOptions()&!product.showExpandedOptions"/>
        <widget module="WholesaleTrading" template="modules/WholesaleTrading/expanded_options.tpl" IF="product.hasOptions()&product.showExpandedOptions"/>
		<widget module="WholesaleTrading" template="modules/WholesaleTrading/extra.tpl">
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr IF="availableForSale" id="addToCartButton">
            <td colspan=2>
                <widget class="CButton" label="Add to Cart" href="javascript: if (isValid()) document.add_to_cart.submit()" img="cart4button.gif" font="FormButton">
            </td>
        </tr>
EOT;
    $replace_str = <<<EOT
        <tr IF="{product.sku}"><td width="30%" class="ProductDetails">SKU:</td><td class="ProductDetails" nowrap>{product.sku}</td></tr>
        
        <widget module="InventoryTracking" template="modules/InventoryTracking/product_quantity.tpl" IF="!product.productOptions" visible="{product.inventory.found}"/>
        <widget module="ProductOptions" template="modules/ProductOptions/product_quantity.tpl" visible="{product.inventory.found}"/>

        <widget class="CExtraFields" product="{product}">
        <tr IF="{!product.weight=0}"><td width="30%" class="ProductDetails">Weight:</td><td class="ProductDetails" nowrap>{product.weight} {config.General.weight_symbol}</td></tr>
        <tr><td width="30%" class="ProductPriceTitle">Price:</td><td class="ProductPrice" nowrap>{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></td></tr>

        <widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/product_button.tpl" visible="{!priceNotificationSaved}">
        <widget module="ProductOptions" template="modules/ProductOptions/product_options.tpl" IF="product.hasOptions()&!product.showExpandedOptions"/>
        <widget module="WholesaleTrading" template="modules/WholesaleTrading/expanded_options.tpl" IF="product.hasOptions()&product.showExpandedOptions"/>
		<widget module="WholesaleTrading" template="modules/WholesaleTrading/extra.tpl">
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr IF="availableForSale" id="addToCartButton">
            <td>
                <widget class="CButton" label="Add to Cart" href="javascript: if (isValid()) document.add_to_cart.submit()" img="cart4button.gif" font="FormButton">
			</td>
			<td IF="!config.General.add_on_mode">
				<widget module="WishList" template="modules/WishList/add.tpl" href="javascript: WishList_Add2Cart();">
            </td>
        </tr>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</table>
</form>


EOT;
    $replace_str = <<<EOT
</table>
</form>

<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/notify_form.tpl" visible="{!priceNotificationSaved}">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
