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
<tbody FOREACH="split(pabpager.pageData,config.ProductAdviser.pab_columns),row">
<tr>
	<td FOREACH="row,PAB" align="center" width="{getPercents(config.ProductAdviser.pab_columns)}%" valign="top">
        <a href="cart.php?target=product&amp;product_id={PAB.product.product_id}&amp;category_id={PAB.product.category.category_id}" IF="PAB.product&config.General.show_thumbnails&PAB.product.hasThumbnail()"><img src="{PAB.product.thumbnailURL}" border=0 width=70 alt=""></a>
    </td>
</tr>    
<tr>
	<td FOREACH="row,PAB" align="center" width="{getPercents(config.ProductAdviser.pab_columns)}%" valign="top">
    	<a IF="PAB.product" href="cart.php?target=product&amp;product_id={PAB.product.product_id}&amp;category_id={PAB.product.category.category_id}"><FONT class="ProductTitle">{PAB.product.name:h}</FONT></a>
	</td>
</tr>        
<tr>
	<td FOREACH="row,PAB" align="center" width="{getPercents(config.ProductAdviser.pab_columns)}%" valign="top">
    	<span IF="PAB.product&config.ProductAdviser.pab_show_price"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(PAB.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {PAB.product.priceMessage:h}</FONT><br></span>
	</td>
</tr>
<tr>
	<td FOREACH="row,PAB" align="center" width="{getPercents(config.ProductAdviser.pab_columns)}%" valign="top">
    	<div IF="PAB.product&config.ProductAdviser.pab_show_buynow">
        <widget class="XLite_View_Button" label="Add to Cart" href="javascript: Add2Cart('{PAB.product.product_id}')" img="cart4button.gif" font="FormButton">
    	</div>
    </td>
</tr>    
<tr>
	<td FOREACH="row,PAB" align="center" width="{getPercents(config.ProductAdviser.pab_columns)}%" valign="top">
    	<span IF="PAB.product">
        <br><br>
    	</span>
    </td>
</tr>    
</tbody>
</table>

<widget name="pabpager">
