{* Product list inside a category ('icons' mode) *}

<widget class="CPager" data="{category.products}" name="pager">

<table border="0" width="100%" cellpadding="3" cellspacing="0">
<tbody FOREACH="split(pager.pageData,3),row">
<tr>
	<td align="center" valign="top" FOREACH="row,product" width="33%">

		<table IF="{product}" border="0" width="200">
		<tr>
		<td><img src="images/spacer.gif" width="1" height="200" alt=""></td>
		<td width="100%" valign="top" style="background-image:url('{xlite.layout.path}images/modules/LayoutOrganizer/forest_green/leaf.gif'); background-repeat: no-repeat; background-position: top 20px;">

			<table cellpadding="0" cellspacing="0">
			<tr>
				<td><img src="images/spacer.gif" width="1" height="13" alt=""></td>
			</tr>
			</table>

			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><img src="images/spacer.gif" width="1" height="50" alt=""></td>
				<td valign="top" align="center"><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}"><FONT style="FONT-WEIGHT: bold; FONT-SIZE: 12px; COLOR: #506176;">{product.name:h}</FONT></a></td>
			</tr>
			</table>

			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><img src="images/spacer.gif" width="35" height="1" alt=""></td>
				<td width="100%" align="left"><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}" IF="product&config.General.show_thumbnails&product.hasThumbnail()"><img src="{product.thumbnailURL}" border=0 width=70 alt=""></a></td>
			</tr>
			</table>

			<table cellpadding="0" cellspacing="0">
			<tr>
				<td><img src="images/spacer.gif" width="1" height="10" alt=""></td>
			</tr>
			</table>

			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><img src="images/spacer.gif" width="35" height="1" alt=""></td>
				<td width="100%" align="left"><span IF="product&config.LayoutOrganizer.show_price"><FONT style="FONT-WEIGHT: bold; FONT-SIZE: 12px; COLOR: #506176;">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT><br><widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" product="{product}" visible="{!getPriceNotificationSaved(product.product_id)}"><br></span></td>
			</tr>
			<tr>
				<td><img src="images/spacer.gif" width="35" height="1" alt=""></td>
				<td width="100%" align="left"><widget template="buy_now.tpl" product="{product}"/>&nbsp;</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>
	</td>
</tr>    
</tbody>
</table>

<widget name="pager">

<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/notify_form.tpl">
