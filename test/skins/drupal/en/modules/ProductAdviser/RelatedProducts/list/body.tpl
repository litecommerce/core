<widget template="modules/ProductAdviser/RelatedProducts/add2cart.tpl">

<widget class="XLite_Module_ProductAdviser_View_Pager" data="{product.RelatedProducts}" name="pager" itemsPerPage="{config.General.products_per_page}">

<table cellpadding="0" cellspacing="0" border="0">
<tbody FOREACH="pager.pageData,RP">
<tr>
	<td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
		<!-- Product thumbnail -->
		<a href="{buildURL(#product#,##,_ARRAY_(#product_id#^RP.product.product_id,#category_id#^RP.product.category.category_id))}" IF="RP.product.hasThumbnail()"><img src="{RP.product.thumbnailURL}" border=0 width=70 alt=""></a>
	</td>
	<td valign=top>
	<a href="{buildURL(#product#,##,_ARRAY_(#product_id#^RP.product.product_id,#category_id#^RP.product.category.category_id))}"><FONT class="ProductTitle">{RP.product.name:h}</FONT></a>
	<span IF="config.ProductAdviser.rp_show_descr&RP.product.brief_description">
	<br>
	{truncate(RP.product,#brief_description#,#300#):h}<br>
	</span>    
	<br IF="config.ProductAdviser.rp_show_price|config.ProductAdviser.rp_show_buynow" />
	<table cellpadding="0" cellspacing="0" border="0">
    <tr>
    	<td IF="config.ProductAdviser.rp_show_price">
    	<FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(RP.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {RP.product.priceMessage:h}</FONT>
    	</td>
    	<td IF="config.ProductAdviser.rp_show_buynow">
    	&nbsp;&nbsp;
    	</td>
    	<td IF="config.ProductAdviser.rp_show_buynow">
		<div IF="!config.ProductAdviser.rp_bulk_shopping">
		<widget class="XLite_View_Button" label="Add to Cart" href="javascript: Add2Cart('{RP.product.product_id}')" type="button" />
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
			<widget class="XLite_View_Button" label="Add to Cart" href="javascript: Add2Cart('{RP.product.product_id}',true)" type="button" />
        	</td>
        </tr>
    	</table>
		</div>
    	</td>
        <td></td>
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
<tbody IF="config.ProductAdviser.rp_show_buynow&config.ProductAdviser.rp_bulk_shopping">
<tr>
	<td colspan=2>&nbsp;</td>
</tr>
<tr>
	<td colspan=2 height=1 class="TableHead"></td>
</tr>
<tr>
	<td colspan=2>&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
    	<table cellpadding="0" cellspacing="0" border="0" IF="showBulkAddForm">
        <tr>
        	<td>
			<widget class="XLite_View_Button" label="Add to Cart" href="javascript: BulkAdd2Cart()" type="button" />
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
