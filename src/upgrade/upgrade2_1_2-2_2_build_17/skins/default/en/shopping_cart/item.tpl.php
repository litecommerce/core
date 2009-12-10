<?php
    $find_str = <<<EOT
        <FONT IF="{item.sku}" class="ProductDetails">SKU: {item.sku}<br></FONT>
        <FONT class="ProductPriceTitle">Price:</FONT> <FONT class="ProductPriceConverting">{price_format(item,#price#):h}&nbsp;x&nbsp;</FONT>
        <input type="text" name="amount[{cart_id}]" value="{item.amount}" size="2" maxlength="3">
        <FONT class="ProductPriceConverting">&nbsp;=&nbsp;</FONT>
        <FONT class="ProductPrice">{price_format(item,#total#):h}</FONT>
        <br>
        <br>
        <widget class="CButton" label="Delete item" href="cart.php?target=cart&action=delete&cart_id={cart_id}" font="FormButton">
    </td>
</tr>
</table>
EOT;
    $replace_str = <<<EOT
        <FONT IF="{item.sku}" class="ProductDetails">SKU: {item.sku}<br></FONT>
        <FONT class="ProductPriceTitle">Price:</FONT> <FONT class="ProductPriceConverting">{price_format(item,#price#):h}&nbsp;x&nbsp;</FONT>
        <input type="text" name="amount[{cart_id}]" value="{item.amount}" size="3" maxlength="6">
        <FONT class="ProductPriceConverting">&nbsp;=&nbsp;</FONT>
        <FONT class="ProductPrice">{price_format(item,#total#):h}</FONT>
        <widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/cart_item.tpl" visible="{xlite.PA_InventorySupport}">
        <br>
        <br>
        <widget class="CButton" label="Delete item" href="cart.php?target=cart&action=delete&cart_id={cart_id}" font="FormButton">
        <span IF="!item.valid"><font class="ProductPriceSmall"><br>(!) This product is out of stock or it has been disabled for sale.</font></span>
    </td>
</tr>
</table>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
