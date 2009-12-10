<!-- [begin] -->
<HTML>

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

<BODY leftmargin=0 topmargin=0 rightmargin=0 bottommargin=0 marginwidth=0 marginheight=0>
<!-- [/begin] -->
<TABLE border="0" width="100%" height="100%" cellpadding=0 cellspacing=0>
<TR>
<TD valign="top">

<TABLE width="100%" border=0 align=center cellpadding=0 cellspacing=0>
<TR>
<TD>
<!-- [top] -->

<TABLE width="100%" border=0 cellpadding=0 cellspacing=0 class="Wallpaper">
<TR>
   <TD height=169 valign=top>

<TABLE width="100%" height=169 border=0 cellpadding=0 cellspacing=0>
<TR>
<TD width=275>
<IMG src="images/logo_white.gif" width=275 height=60 border="0">
</TD>
<TD valign=top>
<TABLE border=0 cellpadding=0 cellspacing=16 align=right>
<TR>
<TD nowrap align=center>
<!-- [phones] -->
<widget template="phones.tpl">
<!-- [/phones] -->
</TD>
</TR>
</TABLE>
</TD>
</TR>

<TR>
<TD height=30>

<!-- [search] -->
<widget template="search_products.tpl">
<!-- [/search] -->

</TD>
<TD>

<!-- [tabs] {{{ -->
<TABLE border=0 cellpadding=0 cellspacing=0 align=center>
<TR>
<TD>
<widget template="common/tab.tpl" label="Catalog" href="cart.php" img="sideicon_orders.gif">
</TD>
<TD>
<widget if="cart.empty" template="common/tab.tpl" label="Your Cart" href="cart.php?target=cart" img="sideicon_cart.gif"/>
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
<TD>
<widget template="common/tab.tpl" label="Contact Us" href="cart.php?target=help&mode=contactus" img="sideicon_profile.gif">
</TD>
<TD>
<widget module="Affiliate" template="common/tab.tpl" label="Affiliate" href="cart.php?target=partner_login" img="modules/Affiliate/sideicon_aff.gif">
</TD>
<!-- add_tabs below are reserved for future modules, DO NOT BIND -->
<TD>
<!-- [add_tab1] -->
</TD>
<TD>
<!-- [add_tab2] -->
</TD>
<TD>
<!-- [add_tab3] -->
</TD>
<!-- Add your tabs here -->
</TR>
</TABLE>
<!-- [tabs] }}} -->

</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

<TABLE width="100%" border=0 cellpadding=0 cellspacing=0>
<TR>
   <TD height=7 background="images/top_line.gif"><IMG src="images/spacer.gif" width=1 height=1 border="0"></TD>
</TR>
</TABLE>
<BR>
<!-- [/top] -->

<!-- [main_view] -->
<TABLE border="0" align="center" cellpadding="0" cellspacing="0">
<TR>
    <TD valign="top">
    <noscript>
        <table border=0 width=500 cellpadding=2 cellspacing=0 align=center>
        <tr>
            <td align=center class=ErrorMessage nowrap>The requested action requires JavaScript.<br>Please enable Javascript in your web browser.</td>
        </tr>
        </table>
    </noscript>
	<script type="text/javascript" language="JavaScript 1.2" src="skins/default/en/js/cookie_validator.js"></script>
    </TD>
</TR>
</TABLE>
<TABLE border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
<TR>
    <TD width="15"><IMG src="images/spacer.gif" width="15" height="1"></TD>
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
<widget target="recover_password" mode="" template="common/dialog.tpl" head="Forgot password?" body="recover_password.tpl">
<widget target="recover_password" mode="recoverMessage" template="common/dialog.tpl" head="Recover password" body="recover_message.tpl">
<widget target="help" mode="contactus" template="common/dialog.tpl" body="contactus.tpl" head="Contact us">
<widget target="help" mode="contactusMessage" template="common/dialog.tpl" body="contactus_message.tpl" head="Message is sent">
<!-- [/help] }}} -->

<!-- [catalog] {{{ -->
<widget target="category" template="category_description.tpl" visible="{category.description}">
<widget target="main,category" mode="" template="common/dialog.tpl" body="{config.General.subcategories_look}" head="Catalog" href="cart.php" visible="{category.subcategories&!page}" showLocationPath>
<widget target="category" template="common/dialog.tpl" body="category_products.tpl" head="Catalog"  href="cart.php" visible="{category.products}" showLocationPath>
<widget target="category" template="common/dialog.tpl" body="category_empty.tpl" head="Catalog"  href="cart.php" visible="{category.empty}" showLocationPath>
<widget target="product" template="common/dialog.tpl" body="product_details.tpl" head="Catalog"  href="cart.php" visible="{product.available}" showLocationPath>
<!-- [/catalog] }}} -->

<!-- [search] {{{ -->
<widget target="search" template="common/dialog.tpl" body="search_result.tpl" head="Search Results">
<!-- [/search] }}} -->

<!-- [shopping_cart] {{{ -->
<widget target="cart" template="common/dialog.tpl" body="shopping_cart/body.tpl" head="Shopping cart">
<!-- [/shopping_cart] }}} -->

<!-- [profile] {{{ -->
<widget target="profile" mode="login" template="common/dialog.tpl" head="Authentication" body="authentication.tpl">
<widget target="profile" mode="account" template="common/dialog.tpl" head="Your account" body="account.tpl">
<widget target="login" template="common/dialog.tpl" body="authentication.tpl" head="Authentication">
<widget target="profile" mode="register" class="CRegisterForm" template="common/dialog.tpl" head="New member" body="register_form.tpl" name="registerForm">
<widget target="profile" mode="success" template="common/dialog.tpl" head="Registration success" body="register_success.tpl">
<widget target="profile" mode="modify" class="CRegisterForm" template="common/dialog.tpl" head="Modify profile" body="profile.tpl" name="profileForm">
<widget target="profile" mode="delete" template="common/dialog.tpl" head="Delete profile - Confirmation" body="delete_profile.tpl">
<!-- [/profile] }}} -->

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

<!-- [order] {{{ -->
<widget target="order_list" template="order/search.tpl">
<widget target="order" template="common/dialog.tpl" body="order/order.tpl" head="Order # {order.order_id}">
<!-- [/order] }}} -->

<!-- [modules] {{{ -->
<widget module="DetailedImages" target="product" template="common/dialog.tpl" body="modules/DetailedImages/body.tpl" head="Detailed Images" visible="{product.detailedImages}">
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
    </TD>
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
    </TD>
    <TD width="15"><IMG src="images/spacer.gif" width="15" height="1"></TD>
</TR>
</TABLE>

<!-- [/main_view] -->
</TD>
</TR>
</TABLE>

</TD>
</TR>

<!-- [align_code] -->
<SCRIPT language="JavaScript">
if (navigator.appName.indexOf('Microsoft') >= 0) {
    document.write('<TR><TD height="100%"><img src="images/spacer.gif" width=1 height=1></TD></TR>');
} else {
    document.write('<TR><TD><img src="images/spacer.gif" width=1 height=1></TD></TR>');
}
</SCRIPT>
<!-- [/align_code] -->

<TR>
<TD>
<!-- [bottom] -->
<TABLE width="100%" border=0 cellpadding=0 cellspacing=0>
<TR>
<TD class="BottomBorder"><IMG src="images/spacer.gif" width=1 height=1 border="0"></TD>
</TR>
<TR>
<TD class="BottomBox">

<TABLE width="70%" border=0 cellpadding=10 cellspacing=0 align=center>
<TR>
<TD colspan=2 align="center" class="BottomMenu">
Help | <A href="cart.php?target=recover_password"><FONT class="BottomMenu">Forgot password?</FONT></A> | <A href="cart.php?target=help&mode=contactus"><FONT class="BottomMenu">Contact us</FONT></A> | <A href="cart.php?target=help&mode=privacy_statement"><FONT class="BottomMenu">Privacy statement</FONT></A> | <A href="cart.php?target=help&mode=terms_conditions"><FONT class="BottomMenu">Terms &amp; Conditions</FONT></A>
<widget template="pages_links.tpl">
</TD>
</TR>
</TABLE>

<TABLE width="100%" border=0 cellpadding=10 cellspacing=0>
<TR>
<TD><FONT class="Bottom">Powered by LiteCommerce:</FONT> <A href="http://www.litecommerce.com"><FONT class="Bottom"><u>ecommerce software</u></FONT></A>
</TD>
<TD align=right><FONT class="Bottom">Copyright &copy; {config.Company.start_year} {config.Company.company_name}</FONT>
</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>
<!-- [/bottom] -->

</TD>
</TR>
</TABLE>

<!-- [end] -->
</BODY>
</HTML>
<!-- [/end] -->
