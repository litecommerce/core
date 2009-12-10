<!-- [begin] -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
<LINK IFF="xlite.FlyoutCategoriesEnabled" href="{xlite.layout.path}modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/{xlite.FlyoutCategoriesCssPath}" rel="stylesheet" type="text/css">
</HEAD>
<!-- [/head] -->

<BODY>
<!-- [/begin] -->
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0" class="Container">
<TR>
<TD valign="top">

<TABLE width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<TR>
<TD>
<!-- [top] -->

<TABLE width="100%" border="0" cellpadding="0" cellspacing="0" class="Wallpaper">
<TR>
   <TD valign=top>

<TABLE width="100%" border="0" cellpadding="0" cellspacing="0">
<TR>
	<TD width="275" valign="top">
		<TABLE border="0" cellpadding="0" cellspacing="0">
		<TR>
			<!--TD><IMG src="images/spacer.gif" width="40" height="122" border="0" hspace="0" vspace="0" alt=""></TD-->
			<TD><IMG src="images/spacer.gif" width="40" height="96" border="0" hspace="0" vspace="0" alt=""></TD>
			<!--TD valign="top"><A href="cart.php"><IMG src="images/logo_white.gif" width="207" height="53" border="0" hspace="0" vspace="28" border="0" alt=""></A></TD-->
			<TD valign="top" style="padding-top: 26px;"><A href="cart.php"><IMG src="images/logo_white.gif" width="207" height="53" border="0" hspace="0" vspace="0" alt=""></A></TD>
		</TR>
		</TABLE>
	</TD>
	<TD valign="top">
		<TABLE border="0" cellpadding="0" cellspacing="16" align="right">
		<TR>
			<TD nowrap align="center">
			<!-- [phones] -->
			<widget template="phones.tpl">
			<!-- [/phones] -->
			</TD> 
		</TR>
		</TABLE>
	</TD>
</TR>

<TR>
<TD class="MainHeaderBG">
&nbsp;
</TD>
<TD class="MainHeaderBG">

<!-- [tabs] {{{ -->
<TABLE border="0" cellpadding="0" cellspacing="0" align="right">
<TR>
<TD>
<widget template="common/tab.tpl" label="Catalog" href="cart.php" img="sideicon_orders.gif" active="main">
</TD>
<TD>
<widget if="cart.empty" template="common/tab.tpl" label="Your Cart" href="cart.php?target=cart" active="cart"/>
<widget if="!cart.empty" template="common/tab.tpl" label="Your Cart" href="cart.php?target=cart" active="cart"/>
</TD>
<TD>
<widget module="WishList" visible="{auth.logged&wishlist.products}" template="common/tab.tpl" label="Wish list" href="cart.php?target=wishlist" img="modules/WishList/wish_list_icon.gif" active="wishlist">
<widget module="WishList" visible="{auth.logged&!wishlist.products}" template="common/tab.tpl" label="Wish list" href="cart.php?target=wishlist" img="modules/WishList/wish_list_icon_empty.gif" active="wishlist">
</TD>
<TD>
<widget if="auth.logged" template="common/tab.tpl" label="Your Account" href="cart.php?target=profile&amp;mode=account" active="profile"/>
<widget if="!auth.logged" template="common/tab.tpl" label="Your Account" href="cart.php?target=profile&amp;mode=login" active="profile"/>
</TD>
<TD>
<widget template="common/tab.tpl" label="Contact Us" href="cart.php?target=help&amp;mode=contactus" active="help"/>
</TD>
<TD>
<widget module="Affiliate" template="common/tab.tpl" label="Affiliate" href="cart.php?target=partner_login" active="partner_login"/>
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
<TD><IMG src="images/tab_terminator.gif" width="1" height="42" border="0" alt=""></TD>
<!--TD><IMG src="images/spacer.gif" width="1" height="1" border="0" alt=""></TD-->
</TR>
</TABLE>
<!-- [tabs] }}} -->

</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_flat.tpl">
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
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0">
<TR>
    <TD width="4"><IMG src="images/spacer.gif" width="4" height="1" alt=""></TD>
	<TD width="180" valign="top">
<!-- [left] -->
<!-- [search] -->
<widget template="search_products.tpl">
<!-- [/search] -->
<div IF="xlite.FlyoutCategoriesEnabled">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_side.tpl">
</div>
<div IF="!xlite.FlyoutCategoriesEnabled">
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories">
</div>
<widget module="Bestsellers" class="CBestsellers" template="common/sidebar_box.tpl" head="Bestsellers" dir="modules/Bestsellers/menu" visible="{config.Bestsellers.bestsellers_menu}">
<widget module="GiftCertificates" template="common/sidebar_box.tpl" head="Gift certificates" dir="modules/GiftCertificates/menu">
<widget module="Affiliate" template="common/sidebar_box.tpl" head="Affiliate" dir="modules/Affiliate/menu">
<widget template="common/sidebar_box.tpl" head="Help" dir="help">
<!-- [/left] -->
	<IMG src="images/spacer.gif" width="180" height="1" alt="">
    </TD>

	<TD width="15"><IMG src="images/spacer.gif" width="15" height="1" alt=""></TD>
    <TD valign="top" width="100%"><BR>

<!-- [center] -->

<!-- [main] {{{ -->
<widget module="InventoryTracking" target="cart" mode="exceeding" template="common/dialog.tpl" body="modules/InventoryTracking/exceeding.tpl" head="InventoryTracking Notification">
<widget module="WishList" mode="wishlist" template="common/dialog.tpl" body="modules/WishList/wishlist_note.tpl" head="Wishlist Notification">
<widget target="main" mode="accessDenied" template="access_denied.tpl">
<widget module="GreetVisitor" target="main" mode="" template="modules/GreetVisitor/greet_visitor.tpl" visible="{greetVisitor&!page}">
<widget target="main" mode="" template="welcome.tpl" name="welcomeWidget" visible="{!page}">
<widget target="main" template="pages.tpl">
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
<widget target="search" template="common/dialog.tpl" body="search_result.tpl" head="Search Result">
<!-- [/search] }}} -->

<!-- [shopping_cart] {{{ -->
<widget target="cart" template="common/dialog.tpl" body="shopping_cart/body.tpl" head="Shopping cart">
<!-- [/shopping_cart] }}} -->

<!-- [profile] {{{ -->
<widget target="profile" mode="login" template="common/dialog.tpl" head="Authentication" body="authentication.tpl">
<widget target="profile" mode="account" template="common/dialog.tpl" head="Your account" body="account.tpl">
<widget target="login" template="common/dialog.tpl" body="authentication.tpl" head="Authentication">
<widget target="profile" mode="register" class="CRegisterForm" template="common/dialog.tpl" head="New customer" body="register_form.tpl" name="registerForm">
<widget target="profile" mode="success" template="common/dialog.tpl" head="Registration complete" body="register_success.tpl">
<widget target="profile" mode="modify" class="CRegisterForm" template="common/dialog.tpl" head="Modify profile" body="profile.tpl" name="profileForm">
<widget target="profile" mode="delete" template="common/dialog.tpl" head="Delete profile - Confirmation" body="delete_profile.tpl">
<!-- [/profile] }}} -->

<!-- [checkout] {{{ -->
<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart">
<widget module="PayPalPro" target="checkout" mode="register" template="common/dialog.tpl" body="modules/PayPalPro/retrieve_profile.tpl" head="Make checkout easier with PayPal Website Pro" visible="{!xlite.PayPalProSolution=#standard#}">
<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="Customer Information" name="registerForm" allowAnonymous="{config.General.enable_anon_checkout}">
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
<widget module="AdvancedSearch" mode="" target="advanced_search" head="Search for products" template="common/dialog.tpl" body="modules/AdvancedSearch/advanced_search.tpl">
<widget module="AdvancedSearch" target="advanced_search" mode="found" template="common/dialog.tpl" body="search_result.tpl" head="Search Result">
<widget module="WishList" target="wishlist,product" mode="MessageSent" template="common/dialog.tpl" body="modules/WishList/message.tpl" head="Message has been sent">
<widget module="WishList" target="wishlist" head="Wish List" template="common/dialog.tpl" body="modules/WishList/wishlist.tpl">
<widget module="WishList" target="product" head="Send to a friend" template="common/dialog.tpl" body="modules/WishList/send_to_friend.tpl">
<widget module="ProductAdviser" template="modules/ProductAdviser/main.tpl">
<!-- [/modules] }}} -->

<!-- [/center] -->
    </TD>
    <TD width="15"><IMG src="images/spacer.gif" width="15" height="1" alt=""></TD>
    <TD width="170" valign="top"><BR>
<!-- [right] -->
<widget module="SnsIntegration" template="modules/SnsIntegration/tracker.tpl">
<widget template="common/sidebar_box.tpl" dir="mini_cart" head="Shopping cart">
<widget template="common/sidebar_box.tpl" dir="login" name="loginWidget" head="Authentication" visible="{!auth.logged}">
<widget template="common/sidebar_box.tpl" dir="profile" name="profileWidget" head="Your profile" visible="{auth.logged}">
<widget template="common/sidebar_box.tpl" dir="authentication" name="authenticationWidget" head="Authentication" visible="{auth.logged}">
<widget module="Newsletters" template="common/sidebar_box.tpl" dir="modules/Newsletters/menu_news" head="News">
<widget module="ProductAdviser" target="main,category,product,cart,RecentlyViewed" class="CNewArrivalsProducts" template="common/sidebar_box.tpl" head="New Arrivals" dir="modules/ProductAdviser/NewArrivals">
<widget module="ProductAdviser" target="main,category,product,cart" class="CRecentliesProducts" template="common/sidebar_box.tpl" head="Recently viewed" dir="modules/ProductAdviser/RecentlyViewed">
<!-- [/right] -->
    </TD>
    <TD width="4"><IMG src="images/spacer.gif" width="4" height="1" alt=""></TD>
</TR>
</TABLE>

<!-- [/main_view] -->
</TD>
</TR>
</TABLE>

</TD>
</TR>

<TR><TD height="100%"><img src="images/spacer.gif" width=1 height=1 alt=""></TD></TR>

<TR>
<TD>
<!-- [bottom] -->
<TABLE width="100%" border="0" cellpadding="0" cellspacing="0">
<TR>
<TD class="BottomBorder"><IMG src="images/spacer.gif" width=1 height=1 border="0" alt=""></TD>
</TR>
<TR>
<TD class="BottomBox">

<TABLE width="70%" border="0" cellpadding=10 cellspacing="0" align="center">
<TR>
<TD colspan=2 align="center" class="BottomMenu">
Help |  <A href="cart.php?target=help&amp;mode=contactus"><FONT class="BottomMenu">Contact us</FONT></A> | <A href="cart.php?target=help&amp;mode=privacy_statement"><FONT class="BottomMenu">Privacy statement</FONT></A> | <A href="cart.php?target=help&amp;mode=terms_conditions"><FONT class="BottomMenu">Terms &amp; Conditions</FONT></A>
<widget template="pages_links.tpl">
</TD>
</TR>
</TABLE>

<TABLE width="100%" border="0" cellpadding=10 cellspacing="0">
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
<div IF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_footer.tpl">
</div>
</body>
</HTML>
<!-- [/end] -->






