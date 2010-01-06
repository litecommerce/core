<widget template="modules/ProductAdviser/RelatedProducts/add2cart.tpl">

<widget class="XLite_Module_ProductAdviser_View_Pager" data="{product.RelatedProducts}" name="pager" itemsPerPage="{config.General.products_per_page}">

<table cellpadding="3" cellspacing="1" border="0" width="100%">
<tr FOREACH="pager.pageData,rpIdx,RP" class="DialogBox">
	<td width=30><FONT class="ProductTitle">&nbsp;#{inc(rpIdx)}&nbsp;</FONT></td>
	<td width="100%"><a href="cart.php?target=product&amp;product_id={RP.product.product_id}&amp;category_id={RP.product.category.category_id}"><FONT class="ProductTitle">{RP.product.name:h}</FONT></a></td>
	<td nowrap IF="config.ProductAdviser.rp_show_price"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(RP.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {RP.product.priceMessage:h}</FONT></td>
	<td nowrap IF="config.ProductAdviser.rp_show_buynow">
	<div IF="!config.ProductAdviser.rp_bulk_shopping">
	<widget class="XLite_View_Button" label="Add to Cart" href="javascript: Add2Cart('{RP.product.product_id}')" img="cart4button.gif" font="FormButton">
	</div>
	<div IF="config.ProductAdviser.rp_bulk_shopping">
	<table cellpadding="0" cellspacing="0" border="0">
    <tr IF="!RP.product.checkHasOptions()">
    	<td>
    	<input type=checkbox value=1 onClick="this.blur(); MarkRP2Add(this,'{RP.product.product_id}')">
    	</td>
    	<td nowrap>
    	checkmark to add
    	</td>
    </tr>
    <tr IF="RP.product.checkHasOptions()">
    	<td>
		<widget class="XLite_View_Button" label="Add to Cart" href="javascript: Add2Cart('{RP.product.product_id}',true)" img="cart4button.gif" font="FormButton">
    	</td>
    </tr>
	</table>
	</div>
	</td>
</tr>
</table>
<div IF="config.ProductAdviser.rp_show_buynow&config.ProductAdviser.rp_bulk_shopping">
<br>	
	<table cellpadding="0" cellspacing="0" border="0">
    <tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    	<td>
		<widget class="XLite_View_Button" label="Add to Cart" href="javascript: BulkAdd2Cart()" img="cart4button.gif" font="FormButton">
    	<td nowrap>
    	&nbsp;selected product(s)
    	</td>
    </tr>
	</table>
</div>

<widget template="modules/ProductAdviser/RelatedProducts/bulk_products.tpl">

<widget name="pager">
