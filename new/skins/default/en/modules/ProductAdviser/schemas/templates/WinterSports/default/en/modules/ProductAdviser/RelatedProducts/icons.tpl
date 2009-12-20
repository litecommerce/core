<widget template="modules/ProductAdviser/RelatedProducts/add2cart.tpl">

<widget class="CPAPager" data="{product.RelatedProducts}" name="pager" itemsPerPage="{config.General.products_per_page}" extraParameter="pabPageID">

<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(pager.pageData,config.ProductAdviser.rp_columns),row">
<tr>
	<td FOREACH="row,RP" align="center" width="{getPercents(config.ProductAdviser.rp_columns)}%" valign="top">
        <widget visible="{RP.product&config.General.show_thumbnails&RP.product.hasThumbnail()}" template="common/product_thumbnail.tpl" href="cart.php?target=product&product_id={RP.product.product_id}&category_id={RP.product.category.category_id}" thumbnail="{RP.product.thumbnailURL}" margin="5px">
    </td>
</tr>    
<tr>
	<td FOREACH="row,RP" align="center" width="{getPercents(config.ProductAdviser.rp_columns)}%" valign="top">
    	<a IF="RP.product" href="cart.php?target=product&amp;product_id={RP.product.product_id}&amp;category_id={RP.product.category.category_id}"><FONT class="ProductTitle">{RP.product.name:h}</FONT></a>
	</td>
</tr>        
<tr>
	<td FOREACH="row,RP" align="center" width="{getPercents(config.ProductAdviser.rp_columns)}%" valign="top">
    	<span IF="RP.product&config.ProductAdviser.rp_show_price"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(RP.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {RP.product.priceMessage:h}</FONT><br></span>
	</td>
</tr>
<tr>
	<td FOREACH="row,RP" align="center" width="{getPercents(config.ProductAdviser.rp_columns)}%" valign="top">
    	<div IF="RP.product&config.ProductAdviser.rp_show_buynow">
		<div IF="!config.ProductAdviser.rp_bulk_shopping">
		<widget class="CButton" label="Add to Cart" href="javascript: Add2Cart('{RP.product.product_id}')" img="cart4button.gif" font="FormButton">
    	</div>
		<div IF="config.ProductAdviser.rp_bulk_shopping">
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
			<widget class="CButton" label="Add to Cart" href="javascript: Add2Cart('{RP.product.product_id}',true)" img="cart4button.gif" font="FormButton">
        	</td>
        </tr>
    	</table>
		</div>
    	</div>
    </td>
</tr>    
<tr>
	<td FOREACH="row,RP" align="center" width="{getPercents(config.ProductAdviser.rp_columns)}%" valign="top">
    	<span IF="RP.product">
        <br><br>
    	</span>
    </td>
</tr>    
</tbody>
<tbody IF="config.ProductAdviser.rp_show_buynow&config.ProductAdviser.rp_bulk_shopping">
<tr>
	<td colspan="{config.ProductAdviser.rp_columns}" height=1 class="TableHead"></td>
</tr>
<tr>
	<td colspan=2>&nbsp;</td>
</tr>
<tr>
	<td colspan="{config.ProductAdviser.rp_columns}">
    	<table cellpadding="0" cellspacing="0" border="0">
        <tr>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        	<td>
			<widget class="CButton" label="Add to Cart" href="javascript: BulkAdd2Cart()" img="cart4button.gif" font="FormButton">
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
