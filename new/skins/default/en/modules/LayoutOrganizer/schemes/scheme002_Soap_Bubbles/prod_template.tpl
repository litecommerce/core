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

                                                                     <!--games-n-toys/-->
<table border="0" cellpadding="3" cellspacing="0" style="background-image:url('{xlite.layout.path}images/modules/LayoutOrganizer/soap_bubbles/bubles_top_left.gif'); background-repeat: no-repeat;">
<tr>
	<td><img src="images/spacer.gif" width="12" height="3" alt=""></td>
	<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><img src="images/spacer.gif" width="1" height="20" alt=""></td>
			</tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td><img src="images/modules/LayoutOrganizer/soap_bubbles/img_top_border_left.gif" alt="" border="0"></td>
				<td width="100%" style="background:url('{xlite.layout.path}images/modules/LayoutOrganizer/soap_bubbles/img_top_border_filler.gif');"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
				<td><img src="images/modules/LayoutOrganizer/soap_bubbles/img_top_border_right.gif" alt="" border="0"></td>
			</tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td valign="top" style="background:url('{xlite.layout.path}images/modules/LayoutOrganizer/soap_bubbles/img_left_border_filler.gif');"><img src="images/modules/LayoutOrganizer/soap_bubbles/img_left_border_top.gif" border="0" alt=""></td>
				<td width="100%" ><img src="{product.imageURL}" border=0 alt=""></td>
				<td valign="top" style="background:url('{xlite.layout.path}images/modules/LayoutOrganizer/soap_bubbles/img_right_border_filler.gif');"><img src="images/modules/LayoutOrganizer/soap_bubbles/img_right_border_top.gif" border="0" alt=""></td>
			</tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td><img src="images/modules/LayoutOrganizer/soap_bubbles/img_bottom_border_left.gif" alt="" border="0"></td>
				<td width="100%" style="background:url('{xlite.layout.path}images/modules/LayoutOrganizer/soap_bubbles/img_bottom_border_filler.gif');"><img src="images/spacer.gif" border="0" alt=""></td>
				<td><img src="images/modules/LayoutOrganizer/soap_bubbles/img_bottom_border_right.gif" alt=""></td>
			</tr>
			</table>
			</td>
			<td valign="bottom"><img src="images/modules/LayoutOrganizer/soap_bubbles/bubles_bottom_right.gif" alt=""></td>
		</tr>
		</table>
	</td>
	<td><img src="images/spacer.gif" width="20" height="1" alt=""></td>
	<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><img src="images/spacer.gif" width="1" height="20" alt=""></td>
		</tr>
		</table>
	<div style="color:#506176; font-family:Arial,sans-serif; font-size:12px; font-weight:bold;">{product.name:h}</div>

        <widget module="InventoryTracking" mode="out_of_stock" template="modules/InventoryTracking/out_of_stock.tpl" IF="product.productOptions"/>

        <!-- product details -->
        <table id="productDetailsTable" cellpadding=0 cellspacing=0 border=0 width="100%">

        <tr><td class="Line" height=1 colspan=2><img src="images/spacer.gif" width=1 height=1 border=0 alt=""></td></tr>
        <tr><td colspan=2>&nbsp;</td></tr>
        
        <tr id="detailsTitle"><td colspan=2 class="ProductDetailsTitle">Details</td></tr>

        <tr><td class="Line" height=1 colspan=2><img src="images/spacer.gif" width=1 height=1 border=0 alt=""></td></tr>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr IF="{product.sku}"><td width="30%" class="ProductDetails">SKU:</td><td class="ProductDetails" nowrap>{product.sku}</td></tr>
        
        <widget module="InventoryTracking" template="modules/InventoryTracking/product_quantity.tpl" IF="!product.productOptions" visible="{product.inventory.found}"/>

        <widget class="CExtraFields" product="{product}">
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
                <widget class="CButton" label="Add to Cart" href="javascript: if (isValid()) document.add_to_cart.submit()" img="cart4button.gif" font="FormButton">
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

<br>
<table cellpadding=3 cellspacing=0 border=0>
<tr>
<td><img src="images/spacer.gif" width="12" height="3" alt=""></td>
<td id="description" style="color:#373B3D"><span style="font-family:Arial,sans-serif; font-size:12px; font-weight:bold;">Description</span>:<br><br>{description:h}</td>
</tr>
</table>

</form>




