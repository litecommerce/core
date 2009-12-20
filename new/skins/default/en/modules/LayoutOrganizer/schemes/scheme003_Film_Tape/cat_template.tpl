{* Product list inside a category ('icons' mode) *}

<widget class="CPager" data="{category.products}" name="pager">

<div FOREACH="pager.pageData,product">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
    <td IF="config.General.show_thumbnails" style="background-image:url('{xlite.layout.path}images/modules/LayoutOrganizer/film_tape/film_bg.gif');background-repeat: no-repeat;" valign="top" align="center" width="80">
    <!-- Product thumbnail -->
	    <table cellpadding="0" cellspacing="0" border="0">
		 <tr>
			<td><img src="images/spacer.gif" width="1" height="2" alt=""></td>
		 </tr>
		 </table>

		 <table cellpadding="0" cellspacing="0" border="0">
		 <tr>
			<td><img src="images/spacer.gif" width="15" height="1" alt=""></td>
			<td><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}" IF="product.hasThumbnail()"><img src="{product.thumbnailURL}" border=0 width=70 alt=""></a></td>
		 </tr>
		 </table>
        
    </td>
    <td valign="top" width="100%">
    <!-- Product details -->
        <table border="0" width="100%">
        <tr>
            <td><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}"><FONT style="FONT-WEIGHT: bold; FONT-SIZE: 12px; COLOR: #506176;">{product.name:h}</FONT></a><br><br></td>
        </tr>
        <tr>
            <td style="COLOR: #616161; FONT-SIZE: 12px; FONT-FAMILY: Arial;">{truncate(product,#brief_description#,#300#):h}<br><br></td>
        </tr>    
        <tr>
            <td>
				<span IF="config.LayoutOrganizer.show_price">
                <FONT style="FONT-WEIGHT: bold; FONT-SIZE: 12px; COLOR: #506176;">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT>
				<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" product="{product}" visible="{!getPriceNotificationSaved(product.product_id)}">
				<!--AFTER PRICE-->
                <br><br>
				</span>
                <widget template="buy_now.tpl" product="{product}">
				<!--AFTER BUY NOW-->
            </td>
        </tr>    
        </table>
		  <hr>
    </td>
</tr>
</table>
</div>

<widget name="pager">

<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/notify_form.tpl">
