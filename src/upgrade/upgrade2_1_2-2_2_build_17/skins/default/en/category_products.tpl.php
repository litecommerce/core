<?php
    $find_str = <<<EOT

<widget class="CPager" data="{category.products}" name="pager">

<p FOREACH="pager.pageData,product">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
    <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
EOT;
	$replace_str = <<<EOT

<widget class="CPager" data="{category.products}" name="pager">

<div FOREACH="pager.pageData,product">
<p>
<table cellpadding="5" cellspacing="0" border="0">
<tr>
    <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);



    $find_str = <<<EOT
        <tr>
            <td>
                <FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT>
				<!--AFTER PRICE-->
                <br><br>
                <widget template="buy_now.tpl" product="{product}">
				<!--AFTER BUY NOW-->
            </td>
        </tr>    
EOT;
    $replace_str = <<<EOT
        <tr>
            <td>
                <FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT>
                <widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" visible="{!priceNotificationSaved}" product="{product}" visible="{!getPriceNotificationSaved(product.product_id)}">
				<!--AFTER PRICE-->
                <br><br>
                <table cellpadding="0" cellspacing="0" border="0">  
                <tr>    
                    <td><widget template="buy_now.tpl" product="{product}"></td>
                    <td width="40">&nbsp;</td> 
                    <td><widget module="WishList" template="modules/WishList/add.tpl" href="cart.php?target=wishlist&action=add&product_id={product.product_id}"></td>
                </tr>       
                </table> 
				<!--AFTER BUY NOW-->
            </td>
        </tr>    
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
    </td>
</tr>
</table>
</p>

<widget name="pager">

EOT;
    $replace_str = <<<EOT
    </td>
</tr>
</table>
</div>

<widget name="pager">

<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/notify_form.tpl">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
