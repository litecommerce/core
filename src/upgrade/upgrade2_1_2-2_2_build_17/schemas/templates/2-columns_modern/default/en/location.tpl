<font class="NavigationPath">
<a IF="!welcomeWidget.visible" href="cart.php" class="NavigationPath">Home</a>
<span FOREACH="locationPath,cname,curl">&nbsp;::&nbsp;<a href="{curl}" class="NavigationPath">{cname}</a>
</span>
<span IF="target=#login#">&nbsp;::&nbsp;Authentication</span>
<span IF="target=#cart#">&nbsp;::&nbsp;Shopping cart</span>
<span IF="target=#profile#&mode=#login#">&nbsp;::&nbsp;Authentication</span>
<span IF="target=#profile#&mode=#modify#">&nbsp;::&nbsp;Modify profile</span>
<span IF="target=#profile#&mode=#delete#">&nbsp;::&nbsp;Delete profile</span>
<span IF="target=#profile#&mode=#register#">&nbsp;::&nbsp;New member</span>
<span IF="target=#profile#&mode=#success#">&nbsp;::&nbsp;New member</span>
<span IF="target=#checkout#">&nbsp;::&nbsp;Checkout</span>
<span IF="target=#checkoutSuccess#">&nbsp;::&nbsp;Checkout</span>
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
