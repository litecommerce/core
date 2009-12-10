<?php

$search =<<<EOT
<table cellpadding="5" cellspacing="0" border="0">
<tr>
    <td valign="top">
        <span class="Text" IF="product.hasImage()"><img src="{product.getImageURL()}" border=0></span>
    </td>
    <td valign=top>
        <!-- product details -->
		{description:h} <br><br>
        <font class="ProductDetailsTitle">Details</font>
        <hr src="images/spacer.gif" width="100%">
        <font class="ProductDetailsLabel">SKU:</font> <font class="ProductDetails">{product.sku}</font><br>
<FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT>
<!--AFTER PRICE-->

        {product_options.display()}
        {out_of_stock.display()}
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>
        <a href="javascript: if (isValid()) document.add_to_cart.submit()"><font class="FormButton">ADD TO CART &gt;&gt;</font><img src="images/cart.gif" width="19" height="16" border="0" align="absmiddle" alt="add to cart"></a>
<!--AFTER ADD TO CART-->
    </td>

</tr>
</table>
EOT;

$replace =<<<EOT
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
    <td IF="product.hasImage()" valign=top align="left" width=100>
        <img src="{product.imageURL}" border=0>
    </td>
    <td valign=top>
    
        <widget module="InventoryTracking" mode="out_of_stock" template="modules/InventoryTracking/out_of_stock.tpl" IF="product.productOptions"/>

        <!-- product details -->
        <table id="productDetailsTable" cellpadding=0 cellspacing=0 border=0 width="100%">

        <tr id="descriptionTitle"><td colspan=2 class="ProductDetailsTitle">Description</td></tr>

        <tr><td class="Line" height=1 colspan=2><img src="images/spacer.gif" width=1 height=1 border=0></td></tr>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr id="description"><td colspan=2>{description:h}</td></tr>
        <tr><td colspan=2>&nbsp;</td></tr>
        
        <tr id="detailsTitle"><td colspan=2 class="ProductDetailsTitle">Details</td></tr>

        <tr><td class="Line" height=1 colspan=2><img src="images/spacer.gif" width=1 height=1 border=0></td></tr>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr IF="{product.sku}"><td width="30%" class="ProductDetails">SKU:</td><td class="ProductDetails" nowrap>{product.sku}</td></tr>
        
        <widget module="InventoryTracking" template="modules/InventoryTracking/product_quantity.tpl" IF="!product.productOptions" visible="{product.inventory.found}"/>

        <tr IF="{product.weight}"><td width="30%" class="ProductDetails">Weight:</td><td class="ProductDetails" nowrap>{product.weight} {config.General.weight_symbol}</td></tr>
        <tr><td width="30%" class="ProductPriceTitle">Price:</td><td class="ProductPrice" nowrap>{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></td></tr>

        <widget module="ProductOptions" template="modules/ProductOptions/product_options.tpl" IF="product.hasOptions()"/>

        <tr><td colspan=2>&nbsp;</td></tr>
        <tr IF="availableForSale" id="addToCartButton"><td colspan=2><a href="javascript: if (isValid()) document.add_to_cart.submit()"><font class="FormButton">ADD TO CART &gt;&gt;</font><img src="images/cart.gif" width="19" height="16" border="0" align="absmiddle" alt="add to cart"></a></td></tr>

        <!--AFTER ADD TO CART-->

        </table>
    </td>
</tr>    
</table>
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

?>
