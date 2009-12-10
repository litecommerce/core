<?php
    $find_str = <<<EOT
<!-- [head] -->
<HEAD>
    <TITLE>
        LiteCommerce. Powerful PHP shopping cart software
        {if:target=#category#} - {category.name:h}{end:}
        {if:target=#product#} - {product.name:h}{end:}
        {if:target=#cart#} - Your Shopping Cart{end:}
        {if:target=#help#} - Help section{end:}
        {if:target=#checkout#} - Checkout{end:}
        {if:target=#checkoutSuccess#} - Thank you for your order{end:}
        {if:target=#main#&!page=##} - {extraPage.head}{end:}
        {title:h}
    </TITLE>
    <META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <META if="!description" name="description" content="The powerful shopping cart software for web stores and e-commerce enabled stores is based on PHP / PHP4 with SQL database with highly configurable implementation based on templates."/>
    <META if="description" name="description" content="{description:r}"/>
    <META if="keywords" name="keywords" content="{keywords:r}"/>
    <LINK href="skins/default/en/style.css"  rel="stylesheet" type="text/css">
</HEAD>
<!-- [/head] -->
EOT;
    $replace_str = <<<EOT
<!-- [head] -->
<HEAD>
<TITLE>LiteCommerce online store builder
{if:target=#category#&!title=##} - {end:}
{if:target=#product#&!title=##} - {end:}
{if:target=#cart#} - Your Shopping Cart{end:}
{if:target=#help#} - Help section{end:}
{if:target=#checkout#} - Checkout{end:}
{if:target=#checkoutSuccess#} - Thank you for your order{end:}
{if:target=#main#&!page=##} - {extraPage.title:h}{end:}
{title:h}
</TITLE>
<META http-equiv="Content-Type" content="text/html; charset={charset}">
<META IFF="!metaDescription" name="description" content="The powerful shopping cart software for web stores and e-commerce enabled stores is based on PHP / PHP4 with SQL database with highly configurable implementation based on templates.">
<META IFF="metaDescription" name="description" content="{metaDescription:r}">
<META IFF="keywords" name="keywords" content="{keywords:r}">
<LINK href="skins/default/en/style.css"  rel="stylesheet" type="text/css">
</HEAD>
<!-- [/head] -->
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<!-- [phones] -->
<widget template="phones.tpl">
<!-- [/phones] -->
</TD> 
</TR>
</TABLE>
</TD>
EOT;
    $replace_str = <<<EOT
<!-- [phones] -->
<widget template="phones.tpl">
<!-- [/phones] -->
</TD>
</TR>
</TABLE>
</TD>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<widget if="!cart.empty" template="common/tab.tpl" label="Your Cart" href="cart.php?target=cart" img="sideicon_cart_full.gif"/>
</TD>
<TD>
<widget if="auth.logged" template="common/tab.tpl" label="Your Account" href="cart.php?target=profile&mode=account" img="sideicon_auth.gif"/>
<widget if="!auth.logged" template="common/tab.tpl" label="Your Account" href="cart.php?target=profile&mode=login" img="sideicon_auth.gif"/>
</TD>
EOT;
    $replace_str = <<<EOT
<widget if="!cart.empty" template="common/tab.tpl" label="Your Cart" href="cart.php?target=cart" img="sideicon_cart_full.gif"/>
</TD>
<TD>
<widget module="WishList" visible="{auth.logged&wishlist.products}" template="common/tab.tpl" label="Wish list" href="cart.php?target=wishlist" img="modules/WishList/wish_list_icon.gif">
<widget module="WishList" visible="{auth.logged&!wishlist.products}" template="common/tab.tpl" label="Wish list" href="cart.php?target=wishlist" img="modules/WishList/wish_list_icon_empty.gif">
</TD>
<TD>
<widget if="auth.logged" template="common/tab.tpl" label="Your Account" href="cart.php?target=profile&mode=account" img="sideicon_auth.gif"/>
<widget if="!auth.logged" template="common/tab.tpl" label="Your Account" href="cart.php?target=profile&mode=login" img="sideicon_auth.gif"/>
</TD>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
    <TD valign="top" width="100%">

<!-- [center] -->

<!-- [main] {{{ -->
<widget target="main" mode="accessDenied" template="access_denied.tpl">
<widget module="GreetVisitor" target="main" mode="" template="modules/GreetVisitor/greet_visitor.tpl" visible="{greetVisitor&!page}">
<widget target="main" template="pages.tpl">
<!-- [/main] }}} -->

<!-- [help] {{{ -->
<widget target="help" mode="terms_conditions" template="common/dialog.tpl" body="terms_conditions.tpl" head="Terms & Conditions">
<widget target="help" mode="privacy_statement" template="common/dialog.tpl" body="privacy_statement.tpl" head="Privacy statement">
<widget target="recover_password" mode="" template="common/dialog.tpl" head="Forgot password?" body="recover_password.tpl">
<widget target="recover_password" mode="recoverMessage" template="common/dialog.tpl" head="Recover password" body="recover_message.tpl">
<widget target="help" mode="contactus" template="common/dialog.tpl" body="contactus.tpl" head="Contact us">
<widget target="help" mode="contactusMessage" template="common/dialog.tpl" body="contactus_message.tpl" head="Message is sent">
EOT;
    $replace_str = <<<EOT
    <TD valign="top" width="100%">

<!-- [center] -->
<widget module="InventoryTracking" target="cart" mode="exceeding" template="common/dialog.tpl" body="modules/InventoryTracking/exceeding.tpl" head="InventoryTracking Notification">
<widget module="WholesaleTrading" mode="update_error" template="common/dialog.tpl" body="modules/WholesaleTrading/update_error.tpl" head="Product quantities not changed">

<!-- [main] {{{ -->
<widget module="WishList" mode="wishlist" template="common/dialog.tpl" body="modules/WishList/wishlist_note.tpl" head="Wishlist Notification">
<widget target="main" mode="accessDenied" template="access_denied.tpl">
<widget target="main" mode="access_denied" template="access_denied.tpl">
<widget module="GreetVisitor" target="main" mode="" template="modules/GreetVisitor/greet_visitor.tpl" visible="{greetVisitor&!page}">
<widget target="main" template="pages.tpl">
<widget module="AdvancedSearch" mode="" target="advanced_search" head="Search for products" template="common/dialog.tpl" body="modules/AdvancedSearch/advanced_search.tpl">
<widget target="advanced_search" mode="found" template="common/dialog.tpl" body="search_result.tpl" head="Search Result">
<!-- [/main] }}} -->

<!-- [help] {{{ -->
<widget target="help" mode="terms_conditions" template="common/dialog.tpl" body="terms_conditions.tpl" head="Terms & Conditions">
<widget target="help" mode="privacy_statement" template="common/dialog.tpl" body="privacy_statement.tpl" head="Privacy statement">
<widget target="recover_password" mode="" template="common/dialog.tpl" head="Forgot your password?" body="recover_password.tpl">
<widget target="recover_password" mode="recoverMessage" template="common/dialog.tpl" head="Recover password" body="recover_message.tpl">
<widget target="help" mode="contactus" template="common/dialog.tpl" body="contactus.tpl" head="Contact us">
<widget target="help" mode="contactusMessage" template="common/dialog.tpl" body="contactus_message.tpl" head="Message is sent">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<!-- [/catalog] }}} -->

<!-- [search] {{{ -->
<widget target="search" template="common/dialog.tpl" body="search_result.tpl" head="Search Result">
<!-- [/search] }}} -->

<!-- [shopping_cart] {{{ -->
EOT;
    $replace_str = <<<EOT
<!-- [/catalog] }}} -->

<!-- [search] {{{ -->
<widget target="search" template="common/dialog.tpl" body="search_result.tpl" head="Search Results">
<!-- [/search] }}} -->

<!-- [shopping_cart] {{{ -->
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<!-- [checkout] {{{ -->
<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart">
<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="New member" name="registerForm" allowAnonymous="{config.General.enable_anon_checkout}">
<widget target="checkout" mode="paymentMethod" template="common/dialog.tpl" body="payment_method.tpl" head="Payment method">
<widget target="checkout" mode="details" template="common/dialog.tpl" body="checkout/details_dialog.tpl" head="{cart.paymentMethod.name}">
<widget target="checkoutSuccess" template="checkout/success.tpl">
<widget target="checkout" mode="notAllowed" template="common/dialog.tpl" body="checkout/not_allowed.tpl" head="Checkout not allowed">
<widget target="checkout" mode="noShipping" template="common/dialog.tpl" body="checkout/no_shipping.tpl" head="No shipping method available">
<widget target="checkout" mode="error" template="common/dialog.tpl" body="checkout/failure.tpl" head="Checkout error">
<!-- [/checkout] }}} -->
EOT;
    $replace_str = <<<EOT
<!-- [checkout] {{{ -->
<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart">
<widget module="PayPalPro" target="checkout" mode="register" template="common/dialog.tpl" body="modules/PayPalPro/retrieve_profile.tpl" head="Make checkout easier with PayPal Website Pro" visible="{!xlite.PayPalProSolution=#standard#}">	
<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="New member" name="registerForm" allowAnonymous="{config.General.enable_anon_checkout}">
<widget target="checkout" mode="paymentMethod" template="common/dialog.tpl" body="payment_method.tpl" head="Payment method">
<widget target="checkout" mode="details" template="common/dialog.tpl" body="checkout/details_dialog.tpl" head="{cart.paymentMethod.name}">
<widget target="checkoutSuccess" template="checkout/success.tpl">
<widget target="checkout" mode="notAllowed" template="common/dialog.tpl" body="checkout/not_allowed.tpl" head="Checkout not allowed">
<widget target="checkout" mode="noShipping" template="common/dialog.tpl" body="checkout/no_shipping.tpl" head="No shipping method available">
<widget target="checkout" mode="noPayment" template="common/dialog.tpl" body="checkout/no_payment.tpl" head="No payment method available">
<widget target="checkout" mode="error" template="common/dialog.tpl" body="checkout/failure.tpl" head="Checkout error">
<!-- [/checkout] }}} -->
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<widget module="Bestsellers" target="main,category" mode="" class="CBestsellers" template="common/dialog.tpl" body="modules/Bestsellers/bestsellers.tpl" head="Bestsellers" visible="{!config.Bestsellers.bestsellers_menu}" name="bestsellerswidget">
<widget module="FeaturedProducts" target="main,category" mode="" template="common/dialog.tpl" body="{config.FeaturedProducts.featured_products_look}" head="Featured products" visible="{category.featuredProducts&!page}">
<widget module="GiftCertificates" target="add_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/add_gift_certificate.tpl" head="Add gift certificate">
<widget module="GiftCertificates" target="gift_certificate_ecards" class="CECardSelect" head="Select e-Card" name="ecardSelectForm">
<widget module="GiftCertificates" target="check_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/check_gift_certificate.tpl" head="Verify gift certificate">
<widget module="Promotion" target="checkout" mode="bonusList" template="common/dialog.tpl" body="modules/Promotion/bonus_list.tpl" head="You have earned bonus">
<widget module="Promotion" target="checkout" template="common/dialog.tpl" body="modules/Promotion/coupon_failed.tpl" head="The discount coupon cannot be used" mode="couponFailed">
<widget module="Promotion" target="cart" template="common/dialog.tpl" body="modules/Promotion/discount_coupon.tpl" head="Discount coupon" visible="{showDCForm}">
<widget module="WholesaleTrading" mode="add_error" template="common/dialog.tpl" body="modules/WholesaleTrading/add_error.tpl" head="Product can not be added">
<widget module="WholesaleTrading" target="profile" mode="success" template="modules/WholesaleTrading/membership/register.tpl">
<widget module="Egoods" template="modules/Egoods/main.tpl">
<widget module="Newsletters" template="modules/Newsletters/newsletters.tpl">

<!-- [/modules] }}} -->

<!-- [/center] -->
EOT;
    $replace_str = <<<EOT
<widget module="Bestsellers" target="main,category" mode="" class="CBestsellers" template="common/dialog.tpl" body="modules/Bestsellers/bestsellers.tpl" head="Bestsellers" visible="{!config.Bestsellers.bestsellers_menu}" name="bestsellerswidget">
<widget module="FeaturedProducts" target="main,category" mode="" template="common/dialog.tpl" body="{config.FeaturedProducts.featured_products_look}" head="Featured products" visible="{category.featuredProducts&!page}">
<widget module="GiftCertificates" target="add_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/add_gift_certificate.tpl" head="Add gift certificate">
<widget module="GiftCertificates" target="gift_certificate_ecards" template="common/dialog.tpl" body="modules/GiftCertificates/select_ecard.tpl" head="Select e-Card">
<widget module="GiftCertificates" target="check_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/check_gift_certificate.tpl" head="Verify gift certificate">
<widget module="GiftCertificates" target="gift_certificate_info" template="common/dialog.tpl" body="modules/GiftCertificates/gift_certificate_info.tpl" head="Gift certificate">
<widget module="Promotion" target="checkout" mode="bonusList" template="common/dialog.tpl" body="modules/Promotion/bonus_list.tpl" head="You qualify for a special offer">
<widget module="Promotion" target="checkout" template="common/dialog.tpl" body="modules/Promotion/coupon_failed.tpl" head="The discount coupon cannot be used" mode="couponFailed">
<widget module="Promotion" target="cart" template="common/dialog.tpl" body="modules/Promotion/discount_coupon.tpl" head="Discount coupon" visible="{showDCForm}">
<widget module="WholesaleTrading" mode="add_error" template="common/dialog.tpl" body="modules/WholesaleTrading/add_error.tpl" head="Product can not be added">
<widget module="WholesaleTrading" target="profile" mode="success" template="modules/WholesaleTrading/membership/register.tpl">
<widget module="Egoods" template="modules/Egoods/main.tpl">
<widget module="Newsletters" template="modules/Newsletters/newsletters.tpl">
<widget module="ProductAdviser" template="modules/ProductAdviser/main.tpl">
<widget module="WishList" target="wishlist,product" mode="MessageSent" template="common/dialog.tpl" body="modules/WishList/message.tpl" head="Message has been sent">
<widget module="WishList" target="wishlist" head="Wish List" template="common/dialog.tpl" body="modules/WishList/wishlist.tpl">
<widget module="WishList" target="product" head="Send to a friend" template="common/dialog.tpl" body="modules/WishList/send_to_friend.tpl">
<!-- [/modules] }}} -->

<!-- [/center] -->
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
    <TD width="15"><IMG src="images/spacer.gif" width="15" height="1"></TD>
    <TD width="15%" valign="top">
<!-- [right] -->
<widget target="main" template="common/sidebar_box.tpl" head="News" dir="menu_news">
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories" visible="{!target=#main#}">
<widget module="Bestsellers" target="main,category" class="CMenuBestsellers" template="common/sidebar_box.tpl" head="Bestsellers" dir="modules/Bestsellers/menu" visible="{config.Bestsellers.bestsellers_menu}">
<widget module="GiftCertificates" template="common/sidebar_box.tpl" head="Gift certificates" dir="modules/GiftCertificates/menu">
<!-- [/right] -->
EOT;
    $replace_str = <<<EOT
    <TD width="15"><IMG src="images/spacer.gif" width="15" height="1"></TD>
    <TD width="15%" valign="top">
<!-- [right] -->
<widget module="SnsIntegration" template="modules/SnsIntegration/tracker.tpl">
<widget target="main" template="common/sidebar_box.tpl" head="News" dir="menu_news">
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories" visible="{!target=#main#}">
<widget module="ProductAdviser" target="main,category,product,cart,RecentlyViewed" class="CNewArrivalsProducts" template="common/sidebar_box.tpl" head="New Arrivals" dir="modules/ProductAdviser/NewArrivals">
<widget module="ProductAdviser" target="main,category,product,cart" class="CRecentliesProducts" template="common/sidebar_box.tpl" head="Recently viewed" dir="modules/ProductAdviser/RecentlyViewed">
<widget module="Bestsellers" target="main,category" class="CMenuBestsellers" template="common/sidebar_box.tpl" head="Bestsellers" dir="modules/Bestsellers/menu" visible="{config.Bestsellers.bestsellers_menu}">
<widget module="GiftCertificates" template="common/sidebar_box.tpl" head="Gift certificates" dir="modules/GiftCertificates/menu">
<!-- [/right] -->
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
    document.write('<TR><TD height="100%"><img src="images/spacer.gif" width=1 height=1></TD></TR>');
} else {
    document.write('<TR><TD><img src="images/spacer.gif" width=1 height=1></TD></TR>');
}    
</SCRIPT>
<!-- [/align_code] -->
EOT;
    $replace_str = <<<EOT
    document.write('<TR><TD height="100%"><img src="images/spacer.gif" width=1 height=1></TD></TR>');
} else {
    document.write('<TR><TD><img src="images/spacer.gif" width=1 height=1></TD></TR>');
}
</SCRIPT>
<!-- [/align_code] -->
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<TR>
<TD><FONT class="Bottom">Powered by LiteCommerce:</FONT> <A href="http://www.litecommerce.com"><FONT class="Bottom"><u>ecommerce software</u></FONT></A>
</TD>
<TD align=right><FONT class="Bottom">Copyright &copy; 2004 {config.Company.company_name}</FONT>
</TD>
</TR>
</TABLE>
EOT;
    $replace_str = <<<EOT
<TR>
<TD><FONT class="Bottom">Powered by LiteCommerce:</FONT> <A href="http://www.litecommerce.com"><FONT class="Bottom"><u>ecommerce software</u></FONT></A>
</TD>
<TD align=right><FONT class="Bottom">Copyright &copy; {config.Company.start_year} {config.Company.company_name}</FONT>
</TD>
</TR>
</TABLE>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
