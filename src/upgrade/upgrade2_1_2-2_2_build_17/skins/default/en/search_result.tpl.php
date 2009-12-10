<?php
    $find_str = <<<EOT
<span IF="!products">
No products found on your query. Please try to re-formulate the query.
</span>

<span IF="products">
<widget class="CPager" data="{products}" name="pager">

<p FOREACH="pager.pageData,product">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
EOT;
    $replace_str = <<<EOT
<span IF="!products">
No products found on your query. Please try to {if:xlite.AdvancedSearchEnabled}<a href ="cart.php?target=advanced_search" class="FormButton"><u>re-formulate</u></a>{else:}re-formulate{end:} the query.
</span>
<span IF="products">
{if:xlite.AdvancedSearchEnabled&count}{dialog.count} {if:count=#1#}product{else:} products {end:} found. <a class="FormButton" href="cart.php?target=advanced_search"><u>Refine your search</u></a>{end:}
<widget class="CPager" data="{products}" name="pager">
<p FOREACH="pager.pageData,product">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
        <FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT>
		<!--AFTER PRICE-->
        <br><br>
        <widget template="buy_now.tpl" product="{product}">
		<!--AFTER BUY NOW-->
    </td>
</tr>
EOT;
    $replace_str = <<<EOT
        <FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT>
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
?>
