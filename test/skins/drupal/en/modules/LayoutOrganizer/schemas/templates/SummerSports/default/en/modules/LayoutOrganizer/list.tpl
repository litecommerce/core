{* Product list inside a category ('list' mode) *}

<widget class="XLite_View_Pager" data="{category.products}" name="pager">

<div FOREACH="pager.pageData,product">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
    <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
    <!-- Product thumbnail -->
	    <widget visible="{product.hasThumbnail()}" template="common/product_thumbnail.tpl" href="cart.php?target=product&product_id={product.product_id}&category_id={category_id}" thumbnail="{product.thumbnailURL}">
        <br><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}" IF="product.hasThumbnail()"><font class="SeeDetails">See&nbsp;details</font></a>
        <br><br>
    </td>
    <td valign="top">
    <!-- Product details -->
        <table border="0">
        <tr>
            <td><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}"><FONT class="ProductTitle">{product.name:h}</FONT></a><br><br></td>
        </tr>
        <tr IF="config.LayoutOrganizer.show_description">
            <td>{truncate(product,#brief_description#,#300#):h}<br><widget template="common/hr.tpl"></td>
        </tr>    
        <tr>
            <td>
				<span IF="config.LayoutOrganizer.show_price">
                <FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT>
				<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" visible="{!getPriceNotificationSaved(product.product_id)}" product="{product}"><!--Should be: widgets/modules_ProductAdviser_PriceNotification_category_button.gif-->
				<!--AFTER PRICE-->
                <br><br>
				</span>
                <widget template="buy_now.tpl" product="{product}">
				<!--AFTER BUY NOW-->
            </td>
        </tr>    
        </table>
    </td>
</tr>
</table>
</div>

<widget name="pager">

<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/notify_form.tpl"><!--Should be: widgets/modules_ProductAdviser_PriceNotification_notify_form.gif-->
