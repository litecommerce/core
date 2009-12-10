<script language="Javascript" type="text/javascript">
<!--

function Add2Cart(product_id)
{
	document.add_to_cart.product_id.value = product_id;
	document.add_to_cart.submit();
}

-->
</script>

<widget class="CPAPager" data="{product.ProductsAlsoBuy}" name="pabpager" itemsPerPage="{config.General.products_per_page}" pageIDX="pabPageID" extraParameter="pageID">

<table cellpadding="1" cellspacing="0" border="0">
<tbody FOREACH="pabpager.pageData,PAB">
<tr>
	<td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
		<!-- Product thumbnail -->
		<widget visible="{PAB.product.hasThumbnail()}" template="common/product_thumbnail.tpl" href="cart.php?target=product&product_id={PAB.product.product_id}&category_id={PAB.product.category.category_id}" thumbnail="{PAB.product.thumbnailURL}" margin="0px 5px 5px 5px">
	</td>
	<td valign=top>
	<a href="cart.php?target=product&amp;product_id={PAB.product.product_id}&amp;category_id={PAB.product.category.category_id}"><FONT class="ProductTitle">{PAB.product.name:h}</FONT></a>
	<span IF="config.ProductAdviser.pab_show_descr&PAB.product.brief_description">
	<br>
	{truncate(PAB.product,#brief_description#,#300#):h}<br>
	</span>    
	<br IF="config.ProductAdviser.pab_show_price|config.ProductAdviser.pab_show_buynow" />
	<table cellpadding="0" cellspacing="0" border="0">
    <tr>
    	<td IF="config.ProductAdviser.pab_show_price">
    	<FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(PAB.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {PAB.product.priceMessage:h}</FONT>
    	</td>
    	<td IF="config.ProductAdviser.pab_show_buynow">
    	&nbsp;&nbsp;
    	</td>
    	<td IF="config.ProductAdviser.pab_show_buynow">
		<widget class="CButton" label="Add to Cart" href="javascript: Add2Cart('{PAB.product.product_id}')" img="cart4button.gif" font="FormButton">
    	</td>
        <td></td>
    </tr>
    </table>
	</td>
</tr>
<tr IF="!PABArrayPointer=PABArraySize">
	<td colspan=2 height=1 class="TableHead"></td>
</tr>
<tr IF="!PABArrayPointer=PABArraySize">
	<td colspan=2>&nbsp;</td>
</tr>
</tbody>
</table>

<widget name="pabpager">
