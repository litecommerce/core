<widget template="modules/ProductAdviser/RelatedProducts/add2cart.tpl">

<widget class="XLite_Module_ProductAdviser_View_Pager" data="{product.RelatedProducts}" name="pager" itemsPerPage="{config.General.products_per_page}" extraParameter="pabPageID">

<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(pager.pageData,getNumberOfColumns()),row">
<tr>
	<td FOREACH="row,RP" align="center" width="{getPercents(getNumberOfColumns())}%" valign="top">
        <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^RP.product.product_id,#category_id#^RP.product.category.category_id))}" IF="RP.product&config.General.show_thumbnails&RP.product.hasThumbnail()"><img src="{RP.product.thumbnailURL}" border=0 width=70 alt=""></a>
    </td>
</tr>    
<tr>
	<td FOREACH="row,RP" align="center" width="{getPercents(getNumberOfColumns())}%" valign="top">
    	<a IF="RP.product" href="{buildURL(#product#,##,_ARRAY_(#product_id#^RP.product.product_id,#category_id#^RP.product.category.category_id))}"><FONT class="ProductTitle">{RP.product.name:h}</FONT></a>
	</td>
</tr>        
<tr>
	<td FOREACH="row,RP" align="center" width="{getPercents(getNumberOfColumns())}%" valign="top">
    	<span IF="RP.product&getShowPrice()"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(RP.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {RP.product.priceMessage:h}</FONT><br></span>
	</td>
</tr>
<tr>
	<td FOREACH="row,RP" align="center" width="{getPercents(getNumberOfColumns())}%" valign="top">
    	<div IF="RP.product&getShowAddToCart()">
		<div IF="!getBulkShopping()">
		<widget class="XLite_View_Button" label="Add to Cart" href="javascript: Add2Cart('{RP.product.product_id}')" img="cart4button.gif" font="FormButton">
    	</div>
		<div IF="getBulkShopping()">
    	<table cellpadding="0" cellspacing="0" border="0">
        <tr IF="!RP.product.checkHasOptions()">
        	<td>
        	<input type=checkbox value=1 onClick="this.blur(); MarkRP2Add(this,'{RP.product.product_id}')">
        	</td>
        	<td>
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
    	</div>
    </td>
</tr>    
<tr>
	<td FOREACH="row,RP" align="center" width="{getPercents(getNumberOfColumns())}%" valign="top">
    	<span IF="RP.product">
        <br><br>
    	</span>
    </td>
</tr>    
</tbody>
<tbody IF="getShowAddToCart()&getBulkShopping()">
<tr>
	<td colspan="{getNumberOfColumns()}" height=1 class="TableHead"></td>
</tr>
<tr>
	<td colspan=2>&nbsp;</td>
</tr>
<tr IF="showBulkAddForm">
	<td colspan="{getNumberOfColumns()}">
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
	</td>
</tr>
</tbody>
</table>

<widget template="modules/ProductAdviser/RelatedProducts/bulk_products.tpl">

<widget name="pager">
