{* Recently viewed body *}

<widget class="XLite_Module_ProductAdviser_View_Pager" data="{recentliesProducts}" name="pager" itemsPerPage="{config.General.products_per_page}">

<table cellpadding="0" cellspacing="0" border="0">
<tbody FOREACH="pager.pageData,RP">
<tr>
	<td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
		<!-- Product thumbnail -->
		<a href="cart.php?target=product&amp;product_id={RP.product.product_id}&amp;category_id={RP.product.category.category_id}" IF="RP.product.hasThumbnail()"><img src="{RP.product.thumbnailURL}" border=0 width=70 alt=""></a>
	</td>
	<td valign=top>
	<a href="cart.php?target=product&amp;product_id={RP.product.product_id}&amp;category_id={RP.product.category.category_id}"><FONT class="ProductTitle">{RP.product.name:h}</FONT></a>
	<br>
	{truncate(RP.product,#brief_description#,#300#):h}<br>
	<br>
	<table cellpadding="0" cellspacing="0" border="0">
    <tr>
    	<td>
    	<FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(RP.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {RP.product.priceMessage:h}</FONT>
    	</td>
    	<td>
    	&nbsp;&nbsp;
    	</td>
    	<td>
    	(<a href="cart.php?target=product&amp;product_id={RP.product.product_id}&amp;category_id={RP.product.category.category_id}"><u>More information</u></a>)
    	</td>
    </tr>
    </table>
	</td>
</tr>
<tr IF="!RPArrayPointer=RPArraySize">
	<td colspan=2 height=1 class="TableHead"></td>
</tr>
<tr IF="!RPArrayPointer=RPArraySize">
	<td colspan=2>&nbsp;</td>
</tr>
</tbody>
</table>

<widget name="pager">
