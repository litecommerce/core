<widget template="modules/ProductAdviser/RelatedProducts/add2cart.tpl">

<widget class="XLite_Module_ProductAdviser_View_Pager" data="{product.RelatedProducts}" name="pager" itemsPerPage="{config.General.products_per_page}">

<table cellpadding="3" cellspacing="1" border="0" bgcolor="#cccccc" width="100%">
<tr FOREACH="pager.pageData,rpIdx,RP" class="DialogBox">
	<td width=30><FONT class="ProductTitle">&nbsp;#{inc(rpIdx)}&nbsp;</FONT></td>
	<td width="100%"><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^RP.product.product_id,#category_id#^RP.product.category.category_id))}"><FONT class="ProductTitle">{RP.product.name:h}</FONT></a></td>
	<td nowrap IF="getShowPrice()"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(RP.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {RP.product.priceMessage:h}</FONT></td>
	<td nowrap IF="getShowAddToCart()">
	<div IF="!getBulkShopping()">
	<widget class="XLite_View_Button" label="Add to Cart" href="javascript: Add2Cart('{RP.product.product_id}')" type="button" />
	</div>
	<div IF="getBulkShopping()">
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
		<widget class="XLite_View_Button" label="Add to Cart" href="javascript: Add2Cart('{RP.product.product_id}',true)" type="button" />
    	</td>
    </tr>
	</table>
	</div>
	</td>
</tr>
</table>
<div IF="showBulkAddForm">
<br>	
	<table cellpadding="0" cellspacing="0" border="0">
    <tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    	<td>
		<widget class="XLite_View_Button" label="Add to Cart" href="javascript: BulkAdd2Cart()" type="button" />
    	<td nowrap>
    	&nbsp;selected product(s)
    	</td>
    </tr>
	</table>
</div>

<widget template="modules/ProductAdviser/RelatedProducts/bulk_products.tpl">

<widget name="pager">
