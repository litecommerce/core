<script language="Javascript" type="text/javascript">
<!--

function Add2Cart(product_id)
{
	document.add_to_cart.product_id.value = product_id;
	document.add_to_cart.submit();
}

-->
</script>

<widget class="XLite_Module_ProductAdviser_View_Pager" data="{product.ProductsAlsoBuy}" name="pabpager" itemsPerPage="{config.General.products_per_page}" pageIDX="pabPageID" extraParameter="pageID">

<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(pabpager.pageData,getNumberOfColumns()),row">
<tr>
	<td FOREACH="row,PAB" align="center" width="{getPercents(getNumberOfColumns())}%" valign="top">
        <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^PAB.product.product_id,#category_id#^PAB.product.category.category_id))}" IF="PAB.product&config.General.show_thumbnails&PAB.product.hasThumbnail()"><img src="{PAB.product.thumbnailURL}" border=0 width=70 alt=""></a>
    </td>
</tr>    
<tr>
	<td FOREACH="row,PAB" align="center" width="{getPercents(getNumberOfColumns())}%" valign="top">
    	<a IF="PAB.product" href="{buildURL(#product#,##,_ARRAY_(#product_id#^PAB.product.product_id,#category_id#^PAB.product.category.category_id))}"><FONT class="ProductTitle">{PAB.product.name:h}</FONT></a>
	</td>
</tr>        
<tr>
	<td FOREACH="row,PAB" align="center" width="{getPercents(getNumberOfColumns())}%" valign="top">
    	<span IF="PAB.product&getShowPrice()"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(PAB.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {PAB.product.priceMessage:h}</FONT><br></span>
	</td>
</tr>
<tr>
	<td FOREACH="row,PAB" align="center" width="{getPercents(getNumberOfColumns())}%" valign="top">
    	<div IF="PAB.product&getShowAddToCart()">
        <widget class="XLite_View_Button" label="Add to Cart" href="javascript: Add2Cart('{PAB.product.product_id}')" type="button" />
    	</div>
    </td>
</tr>    
<tr>
	<td FOREACH="row,PAB" align="center" width="{getPercents(getNumberOfColumns())}%" valign="top">
    	<span IF="PAB.product">
        <br><br>
    	</span>
    </td>
</tr>    
</tbody>
</table>

<widget name="pabpager">
