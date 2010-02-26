{* Product list inside a category *}

<widget class="XLite_View_Pager" data="{category.products}" name="pager">

<div FOREACH="pager.pageData,product">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
    <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
    <!-- Product thumbnail -->
        <a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}" IF="product.hasThumbnail()"><img src="{product.thumbnailURL}" border=0 width=70 alt=""></a> <br><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}" IF="product.hasThumbnail()"><font class="SeeDetails">See&nbsp;details&nbsp;</font><img src="images/details.gif" width="13" height="13" border="0" align="middle" alt=""></a>
        <br><br>
    </td>
    <td valign="top" width="100%">
    <!-- Product details -->
        <table border="0" width="100%">
        <tr>
            <td><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}"><FONT class="ProductTitle">{product.name:h}</FONT></a><br><br></td>
        </tr>
        <tr IF="config.ShowcaseOrganizer.so_show_description">
            <td>{truncate(product,#brief_description#,#300#):h}<br><hr></td>
        </tr>    
        <tr>
            <td>
				<span IF="config.ShowcaseOrganizer.so_show_price">
                <FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT>
				<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" product="{product}" visible="{!getPriceNotificationSaved(product.product_id)}">
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

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm">
