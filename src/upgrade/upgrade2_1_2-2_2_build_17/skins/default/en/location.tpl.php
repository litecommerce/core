<?php
    $find_str = <<<EOT
<span IF="mode=#checkoutNotAllowed#">&nbsp;::&nbsp;Checkout is not allowed</span>
<span IF="target=#order_list#">&nbsp;::&nbsp;Search orders</span>
<span IF="target=#order#">&nbsp;::&nbsp;<a href="cart.php?target=order_list" class="NavigationPath">Search orders</a>&nbsp;::&nbsp;Order details</span>
<span IF="target=#search#">&nbsp;::&nbsp;Search Result</span>
<span IF="target=#help#&mode=#terms_conditions#">&nbsp;::&nbsp;Help zone :: Terms & Conditions</span>
<span IF="target=#help#&mode=#privacy_statement#">&nbsp;::&nbsp;Help zone :: Privacy statement</span>
<span IF="target=#recover_password#">&nbsp;::&nbsp;Help zone :: Recover password</span>
<span IF="target=#help#&mode=#contactus#">&nbsp;::&nbsp;Help zone :: Contact us</span>
<widget module="GiftCertificates" template="modules/GiftCertificates/location.tpl">
</font>
<widget module="Newsletters" template="modules/Newsletters/location.tpl">
EOT;
    $replace_str = <<<EOT
<span IF="mode=#checkoutNotAllowed#">&nbsp;::&nbsp;Checkout is not allowed</span>
<span IF="target=#order_list#">&nbsp;::&nbsp;Search orders</span>
<span IF="target=#order#">&nbsp;::&nbsp;<a href="cart.php?target=order_list" class="NavigationPath">Search orders</a>&nbsp;::&nbsp;Order details</span>
<span IF="target=#search#">&nbsp;::&nbsp;Search Results</span>
<widget module="AdvancedSearch" template="modules/AdvancedSearch/location.tpl">
<span IF="target=#help#&mode=#terms_conditions#">&nbsp;::&nbsp;Help zone :: Terms & Conditions</span>
<span IF="target=#help#&mode=#privacy_statement#">&nbsp;::&nbsp;Help zone :: Privacy statement</span>
<span IF="target=#recover_password#">&nbsp;::&nbsp;Help zone :: Recover password</span>
<span IF="target=#help#&mode=#contactus#">&nbsp;::&nbsp;Help zone :: Contact us</span>
<widget module="GiftCertificates" template="modules/GiftCertificates/location.tpl">
</font>
<widget module="WishList" template="modules/WishList/location.tpl">
<widget module="Newsletters" template="modules/Newsletters/location.tpl">
<widget module="ProductAdviser" template="modules/ProductAdviser/location.tpl">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
