{* Product list inside a category ('icons' mode) *}

<widget class="CPager" data="{category.products}" name="pager">

<table border="0" width="100%" cellpadding="3" cellspacing="0">
<tbody FOREACH="split(pager.pageData,2),row">
<tr>
	<td valign="top" FOREACH="row,product" width="50%" style="background-image:url('{xlite.layout.path}images/modules/LayoutOrganizer/soap_bubbles/bubles.gif');background-repeat: no-repeat;">
		<div IF={product}>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><img src="images/spacer.gif" width="1" height="13" alt=""></td>
		</tr>
		</table>

		<table border=0>
		<tr>
		<td><img src="images/spacer.gif" width="19" height="130" alt=""></td>
		<td align="center" valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><img src="images/modules/LayoutOrganizer/soap_bubbles/img_top_border_left.gif" alt=""></td>
				<td width="100%" style="background:url('{xlite.layout.path}images/modules/LayoutOrganizer/soap_bubbles/img_top_border_filler.gif');"><img src="images/spacer.gif" alt=""></td>
				<td><img src="images/modules/LayoutOrganizer/soap_bubbles/img_top_border_right.gif" alt=""></td>
			</tr>
			</table>
			<table cellpadding="0" cellspacing="0">
			<tr>
				<td valign="top" style="background:url('{xlite.layout.path}images/modules/LayoutOrganizer/soap_bubbles/img_left_border_filler.gif');"><img src="images/modules/LayoutOrganizer/soap_bubbles/img_left_border_top.gif" alt=""></td>
				<td width="100%"><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}" IF="product&config.General.show_thumbnails&product.hasThumbnail()"><img src="{product.thumbnailURL}" border=0 width=70 alt=""></a></td>
				<td valign="top" style="background:url('{xlite.layout.path}images/modules/LayoutOrganizer/soap_bubbles/img_right_border_filler.gif');"><img src="images/modules/LayoutOrganizer/soap_bubbles/img_right_border_top.gif" alt=""></td>
			</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><img src="images/modules/LayoutOrganizer/soap_bubbles/img_bottom_border_left.gif" alt=""></td>
				<td width="100%" style="background:url('{xlite.layout.path}images/modules/LayoutOrganizer/soap_bubbles/img_bottom_border_filler.gif');"><img src="images/spacer.gif" alt=""></td>
				<td><img src="images/modules/LayoutOrganizer/soap_bubbles/img_bottom_border_right.gif" alt=""></td>
			</tr>
			</table>			  

		 </td>
 		<td><img src="images/spacer.gif" width="7" height="1" alt=""></td>
		<td valign="top">
			<table>
			<tr>
				<td><a IF="product" href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}"><FONT class="ProductTitle">{product.name:h}</FONT></a></td>
			</tr>
			<tr>
				<td><span IF="product&config.LayoutOrganizer.show_price"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT><br><widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" product="{product}" visible="{!getPriceNotificationSaved(product.product_id)}"><br></span></td>
			</tr>
			<tr>
				<td><widget IF="product" template="buy_now.tpl" product="{product}"/>&nbsp;</td>
			</tr>
			</table>
			<br><br>
		</td>
		</tr>
		</table>
		</div>
	</td>
</tr>    
</tbody>
</table>

<widget name="pager">

<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/notify_form.tpl">
