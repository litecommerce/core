{* Product list inside a category *}

<widget class="XLite_View_Pager" data="{category.products}" name="pager">

<div FOREACH="pager.pageData,product">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
    <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
    <!-- Product thumbnail -->
        <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" IF="product.hasThumbnail()"><img src="{product.thumbnailURL}" border=0 width=70 alt=""></a> <br><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" IF="product.hasThumbnail()"><font class="SeeDetails">See&nbsp;details&nbsp;</font><img src="images/details.gif" width="11" height="11" border="0" align="middle" alt=""></a>
        <br><br>
    </td>
    <td valign="top">
    <!-- Product details -->
        <table border="0">
        <tr>
            <td><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}"><FONT class="ProductTitle">{product.name:h}</FONT></a><br><br></td>
        </tr>
        <tr>
            <td>{truncate(product,#brief_description#,#300#):h}<br><hr></td>
        </tr>    
        <tr>
            <td>
                <widget class="XLite_View_Price" product="{product}" template="common/price_plain.tpl">
                <widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" product="{product}" visible="{!getPriceNotificationSaved(product.product_id)}">
				<!--AFTER PRICE-->
                <br><br>
                <table cellpadding="0" cellspacing="0" border="0">  
                <tr>    
                    <td><widget template="buy_now.tpl" product="{product}"></td>
                    <td width="40">&nbsp;</td> 
                    <td><widget module="WishList" template="modules/WishList/add.tpl" href="{buildURL(#wishlist#,#add#,_ARRAY_(#product_id#^product.product_id))}" /></td>
                </tr>       
                </table> 
				<!--AFTER BUY NOW-->
            </td>
        </tr>    
        </table>
    </td>
</tr>
</table>
</div>

<widget name="pager">

<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/notify_form.tpl">
