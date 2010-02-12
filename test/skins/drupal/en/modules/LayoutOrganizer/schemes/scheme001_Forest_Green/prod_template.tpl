{* Product description page *}
<script language="JavaScript1.2" type="text/javascript">
<!--
function isValid()
{   
    return true;
}
-->
</script>

<form action="{shopURL(#cart.php#)}" method=GET name="add_to_cart" onSubmit="isValid()">
<input type="hidden" name="target" value="cart">
<input type="hidden" name="action" value="add">
<input type="hidden" name="product_id" value="{product.product_id}">
<input type="hidden" name="category_id" value="{category_id}">


<table border="0" cellpadding="3" cellspacing="0" style="background-image: url('{xlite.layout.path}images/modules/LayoutOrganizer/forest_green/leaf.gif'); background-repeat: no-repeat;">
<tr>
	<td><img src="images/spacer.gif" width="5" height="200" alt=""></td>
	<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><img src="images/spacer.gif" width="1" height="50" alt=""></td>
		</tr>
		</table>

		<table cellpadding="0" cellspacing="0">
		<tr>
			<td width="100%"><img src="{product.imageURL}" border=0 alt=""></td>
		</tr>
		</table>
	</td>
	<td><img src="images/spacer.gif" width="47" height="3" alt=""></td>
   <td valign=top>
    
        <widget module="InventoryTracking" mode="out_of_stock" template="modules/InventoryTracking/out_of_stock.tpl" IF="product.productOptions"/>

        <!-- product details -->
        <table id="productDetailsTable" cellpadding=0 cellspacing=0 border=0 width="100%">

        <tr><td class="Line" height=1 colspan=2><img src="images/spacer.gif" width=1 height=1 border=0 alt=""></td></tr>
        <tr ><td style="FONT-WEIGHT: bold; FONT-SIZE: 12px; COLOR: #506176;" colspan=2>{product.name:h}</td></tr>
		  <tr><td colspan=2>&nbsp;</td></tr>
        <tr id="description"><td style="COLOR: #373B3D;" colspan=2>{description:h}</td></tr>
        <tr><td colspan=2>&nbsp;<br>&nbsp;</td></tr>
        
        <tr id="detailsTitle"><td colspan=2 class="ProductDetailsTitle">Details</td></tr>

        <tr><td class="Line" height=1 colspan=2><img src="images/spacer.gif" width=1 height=1 border=0 alt=""></td></tr>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr IF="{product.sku}"><td width="30%" class="ProductDetails">SKU:</td><td class="ProductDetails" nowrap>{product.sku}</td></tr>
        
        <widget module="InventoryTracking" template="modules/InventoryTracking/product_quantity.tpl" IF="!product.productOptions" visible="{product.inventory.found}"/>

        <widget class="XLite_View_ExtraFields" product="{product}">
        <tbody>
        <tr IF="{!product.weight=0}"><td width="30%" class="ProductDetails">Weight:</td><td class="ProductDetails" nowrap>{product.weight} {config.General.weight_symbol}</td></tr>
        <tr><td width="30%" class="ProductPriceTitle">Price:</td><td class="ProductPrice" nowrap>{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></td></tr>

		<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/product_button.tpl" visible="{!priceNotificationSaved}">
        <widget module="ProductOptions" template="modules/ProductOptions/product_options.tpl" IF="product.hasOptions()&!product.showExpandedOptions"/>
        <widget module="WholesaleTrading" template="modules/WholesaleTrading/expanded_options.tpl" IF="product.hasOptions()&product.showExpandedOptions"/>
		<widget module="WholesaleTrading" template="modules/WholesaleTrading/extra.tpl">
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr IF="availableForSale" id="addToCartButton">
            <td>
                <widget class="XLite_View_Button" label="Add to Cart" href="javascript: if (isValid()) document.add_to_cart.submit()" img="cart4button.gif" font="FormButton">
            </td>
            <td>
                <widget module="WishList" template="modules/WishList/add.tpl" href="javascript: WishList_Add2Cart();">
            </td>
        </tr>

        <!--AFTER ADD TO CART-->

        </table>
    </td>
</tr>    
</table>
</form>




