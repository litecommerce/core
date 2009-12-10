<?php

$source = strReplace('<a href="cart.php" class="NavigationPath">Home</a>', '<a IF="!welcomeWidget.visible" href="cart.php" class="NavigationPath">Home</a>', $source, __FILE__, __LINE__);
$source = strReplace('<span IF="found_product" class="NavigationPath">&nbsp;::&nbsp;<a href="cart.php?target=search&substring={substring:u}">Search Result</a></span>', '', $source, __FILE__, __LINE__);
$source = strReplace('<span FOREACH="location,name,url">&nbsp;::&nbsp;<a href="{url:r}" class="NavigationPath">{name}</a>', '<span FOREACH="locationPath,cname,curl">&nbsp;::&nbsp;<a href="{curl:r}" class="NavigationPath">{cname}</a>', $source, __FILE__, __LINE__);

$search =<<<EOT
<span IF="authentication">&nbsp;::&nbsp;Authentication</span>
<span IF="shopping_cart">&nbsp;::&nbsp;Shopping cart</span>
<span IF="modify_profile">&nbsp;::&nbsp;Modify profile</span>
<span IF="delete_profile">&nbsp;::&nbsp;Delete profile</span>
<span IF="new_member">&nbsp;::&nbsp;New member</span>
<span IF="checkout">&nbsp;::&nbsp;Checkout</span>
<span IF="order_list">&nbsp;::&nbsp;Orders search</span>
<span IF="order">&nbsp;::&nbsp;<a href="cart.php?target=order_list" class="NavigationPath">Search orders</a>&nbsp;::&nbsp;Order details</span>
<span IF="checkout_not_allowed">&nbsp;::&nbsp;Checkout is not allowed</span>
<span IF="search">&nbsp;::&nbsp;Search Result</span>
<span IF="terms_conditions">&nbsp;::&nbsp;Help zone :: Terms & Conditions</span>
<span IF="privacy_statement">&nbsp;::&nbsp;Help zone :: Privacy statement</span>
<span IF="recover_password">&nbsp;::&nbsp;Help zone :: Recover password</span>
<span IF="contactus">&nbsp;::&nbsp;Help zone :: Contact us</span>
EOT;

$replace =<<<EOT
<span IF="target=#login#">&nbsp;::&nbsp;Authentication</span>
<span IF="target=#cart#">&nbsp;::&nbsp;Shopping cart</span>
<span IF="target=#profile#&mode=#modify#">&nbsp;::&nbsp;Modify profile</span>
<span IF="target=#profile#&mode=#delete#">&nbsp;::&nbsp;Delete profile</span>
<span IF="target=#profile#&mode=#register#">&nbsp;::&nbsp;New member</span>
<span IF="target=#profile#&mode=#success#">&nbsp;::&nbsp;New member</span>
<span IF="target=#checkout#">&nbsp;::&nbsp;Checkout</span>
<span IF="target=#checkoutSuccess#">&nbsp;::&nbsp;Checkout</span>
<span IF="mode=#checkoutNotAllowed#">&nbsp;::&nbsp;Checkout is not allowed</span>
<span IF="target=#order_list#">&nbsp;::&nbsp;Search orders</span>
<span IF="target=#order#">&nbsp;::&nbsp;<a href="cart.php?target=order_list" class="NavigationPath">Search orders</a>&nbsp;::&nbsp;Order details</span>
<span IF="target=#search#">&nbsp;::&nbsp;Search Result</span>
<span IF="target=#help#&mode=#terms_conditions#">&nbsp;::&nbsp;Help zone :: Terms & Conditions</span>
<span IF="target=#help#&mode=#privacy_statement#">&nbsp;::&nbsp;Help zone :: Privacy statement</span>
<span IF="target=#recover_password#">&nbsp;::&nbsp;Help zone :: Recover password</span>
<span IF="target=#help#&mode=#contactus#">&nbsp;::&nbsp;Help zone :: Contact us</span>
<widget module="GiftCertificates" template="modules/GiftCertificates/location.tpl">
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

?>
