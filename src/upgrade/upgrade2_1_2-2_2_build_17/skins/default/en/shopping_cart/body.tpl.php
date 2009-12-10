<?php
    $find_str = <<<EOT
<input type="hidden" name="action" value="update">
<p align=justify>The items in your shopping cart are listed below. To remove any item click "Delete Item". To place your order, please click "CHECKOUT".</p>

<p FOREACH="cart.items,cart_id,item">
<widget template="shopping_cart/item.tpl" IF="item.useStandardTemplate"/>
<widget module="GiftCertificates" template="modules/GiftCertificates/item.tpl" IF="item.gcid"/>
</p>
<IMG SRC="images/spacer.gif" class=DialogBorder WIDTH="100%" HEIGHT=1 BORDER="0">
<widget template="shopping_cart/delivery.tpl">
<table border=0 width="100%">
EOT;
    $replace_str = <<<EOT
<input type="hidden" name="action" value="update">
<p align=justify>The items in your shopping cart are listed below. To remove any item click "Delete Item". To place your order, please click "CHECKOUT".</p>

<div FOREACH="cart.items,cart_id,item">
<p>
<widget template="shopping_cart/item.tpl" IF="item.useStandardTemplate"/>
<widget module="GiftCertificates" template="modules/GiftCertificates/item.tpl" IF="item.gcid"/>
</div>
<IMG SRC="images/spacer.gif" class=DialogBorder WIDTH="100%" HEIGHT=1 BORDER="0">
<widget template="shopping_cart/delivery.tpl">
<table border=0 width="100%">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<widget template="shopping_cart/buttons.tpl">
</form>

EOT;
    $replace_str = <<<EOT
<widget template="shopping_cart/buttons.tpl">
</form>

<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
