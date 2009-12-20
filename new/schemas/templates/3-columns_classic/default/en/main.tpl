{* Contains the site layout and the page header *}
<!-- [begin] -->
<html>
<head>
<title>LiteCommerce online store builder
{if:target=#category#&!title=##} - {end:}
{if:target=#product#&!title=##} - {end:}
{if:target=#cart#} - Your Shopping Cart{end:}
{if:target=#help#} - Help section{end:}
{if:target=#checkout#} - Checkout{end:}
{if:target=#checkoutSuccess#} - Thank you for your order{end:}
{if:target=#main#&!page=##} - {extraPage.title:h}{end:}
{title:h}
</title>
<meta http-equiv="Content-Type" content="text/html; charset={charset}">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta IFF="!metaDescription" name="description" content="The powerful shopping cart software for web stores and e-commerce enabled stores is based on PHP / PHP4 with SQL database with highly configurable implementation based on templates.">
<meta IFF="metaDescription" name="description" content="{metaDescription:r}">
<meta IFF="keywords" name="keywords" content="{keywords:r}">
<link href="skins/default/en/style.css"  rel="stylesheet" type="text/css">
<LINK IFF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme" href="{xlite.layout.path}modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/{xlite.FlyoutCategoriesCssPath}" rel="stylesheet" type="text/css">
</head>
<body LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 BOTTOMMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<!-- [/begin] -->
<table width="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0 height="100%">
<tr>
<td valign="top">
<!-- [top] -->
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
   <TD class="Head" background="images/head.gif" HEIGHT=74><IMG SRC="images/logo.gif" WIDTH=275 HEIGHT=60 BORDER="0"></TD>
</TR>
</TABLE>
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
   <TD WIDTH=665 height=20 valign=top><IMG SRC="images/head_line.gif" WIDTH=665 HEIGHT=12 ALT=""></TD>
   <TD WIDTH="100%"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=12 ALT=""></TD>
</TR>
<TR>
    <TD colspan="2"><widget template="search_products.tpl"></TD>
</TR>
</TABLE>
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_flat.tpl">
<BR>
<!-- [/top] -->

<!-- [main_view] -->
<TABLE border="0" align="center" cellpadding="0" cellspacing="0">
<TR>
    <TD valign="top">
    <noscript>
        <table border=0 width=500 cellpadding=2 cellspacing=0 align=center>
        <tr>
            <td align=center class=ErrorMessage nowrap>This site requires JavaScript to function properly.<br>Please enable Javascript in your web browser.</td>
        </tr>
        </table>
    </noscript>
	<script type="text/javascript" language="JavaScript 1.2" src="skins/default/en/js/cookie_validator.js"></script>
    </TD>
</TR>
</TABLE>
<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0" height="100%">
<tr>
    <td width="150" valign="top">
<!-- [left] -->
<widget module="SnsIntegration" template="modules/SnsIntegration/tracker.tpl">
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
    </td>
    <td width="15">&nbsp;</td>
    <td valign="top">
<!-- [center] -->
<widget module="GoogleCheckout" class="CGoogleAltCheckout">
<widget template="location.tpl" name="locationWidget">
<widget module="AdvancedSearch" mode="" target="advanced_search" head="Search for products" template="common/dialog.tpl" body="modules/AdvancedSearch/advanced_search.tpl">
<widget module="AdvancedSearch" target="advanced_search" mode="found" template="common/dialog.tpl" body="search_result.tpl" head="Search Result">
<widget module="InventoryTracking" target="cart" mode="exceeding" template="common/dialog.tpl" body="modules/InventoryTracking/exceeding.tpl" head="InventoryTracking Notification">
<widget module="WholesaleTrading" mode="update_error" template="common/dialog.tpl" body="modules/WholesaleTrading/update_error.tpl" head="Product quantities not changed">
<widget module="WishList" mode="wishlist" template="common/dialog.tpl" body="modules/WishList/wishlist_note.tpl" head="Wishlist Notification">
<widget module="GreetVisitor" target="main" mode="" template="modules/GreetVisitor/greet_visitor.tpl" visible="{greetVisitor&!page}">
<widget target="main" mode="" template="welcome.tpl" name="welcomeWidget" visible="{!page}">
<widget target="main" mode="accessDenied" template="access_denied.tpl">
<widget target="main" mode="access_denied" template="access_denied.tpl">
<widget target="login" template="common/dialog.tpl" body="authentication_error.tpl" head="Authentication">
<widget target="profile" mode="login" template="common/dialog.tpl" head="Authentication" body="authentication.tpl">
<widget target="category" template="category_description.tpl" visible="{category.description}">
<widget target="category" template="common/dialog.tpl" body="{config.General.subcategories_look}" head="{category.name} - Subcategories" visible="{category.subcategories}">
<widget target="category" template="common/dialog.tpl" body="category_products.tpl" head="{category.name} - Product list" visible="{category.products}">
<widget target="category" template="common/dialog.tpl" body="category_empty.tpl" head="{category.name} - Product list" visible="{category.empty}">
<widget target="product" template="common/dialog.tpl" body="product_details.tpl" head="{product.name}" visible="{product.available}">
<widget target="cart" template="common/dialog.tpl" body="shopping_cart/body.tpl" head="Shopping cart">
<widget target="profile" mode="register" class="CRegisterForm" template="common/dialog.tpl" head="New member" IF="!showAV" body="register_form.tpl" name="registerForm">
<widget target="profile" mode="success" template="common/dialog.tpl" head="Registration success" body="register_success.tpl">
<widget target="profile" mode="modify" class="CRegisterForm" template="common/dialog.tpl" head="Modify profile" body="profile.tpl" name="profileForm" IF="!showAV"/>
<widget target="profile" mode="delete" template="common/dialog.tpl" head="Delete profile - Confirmation" body="delete_profile.tpl">
<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart" IF="!showAV"/>
<widget module="PayPalPro" target="checkout" mode="register" template="common/dialog.tpl" body="modules/PayPalPro/retrieve_profile.tpl" head="Make checkout easier with PayPal Website Pro" visible="{xlite.PayPalProExpressEnabled}">
<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="New member" IF="!showAV" name="registerForm" allowAnonymous="{config.General.enable_anon_checkout}">
<widget target="checkout" mode="paymentMethod" template="common/dialog.tpl" body="payment_method.tpl" head="Payment method">
<widget target="checkout" mode="details" template="common/dialog.tpl" body="checkout/details_dialog.tpl" head="{cart.paymentMethod.name}">
<widget target="checkoutSuccess" template="checkout/success.tpl">
<widget target="checkout" mode="notAllowed" template="common/dialog.tpl" body="checkout/not_allowed.tpl" head="Checkout not allowed">
<widget target="checkout" mode="noShipping" template="common/dialog.tpl" body="checkout/no_shipping.tpl" head="No shipping method available">
<widget target="checkout" mode="noPayment" template="common/dialog.tpl" body="checkout/no_payment.tpl" head="No payment method available">
<widget target="checkout" mode="error" template="common/dialog.tpl" body="checkout/failure.tpl" head="Checkout error">
<widget target="order_list" template="order/search.tpl">
<widget target="order" template="common/dialog.tpl" body="order/order.tpl" head="Order # {order.order_id}">
<widget module="DetailedImages" target="product" template="common/dialog.tpl" body="modules/DetailedImages/body.tpl" head="Detailed Images" visible="{product.detailedImages}">
<widget target="search" template="common/dialog.tpl" body="search_result.tpl" head="Search Results">
<widget target="help" mode="terms_conditions" template="common/dialog.tpl" body="terms_conditions.tpl" head="Terms & Conditions">
<widget target="help" mode="privacy_statement" template="common/dialog.tpl" body="privacy_statement.tpl" head="Privacy statement">
<widget target="recover_password" mode="" template="common/dialog.tpl" head="Forgot password?" body="recover_password.tpl">
<widget target="recover_password" mode="recoverMessage" template="common/dialog.tpl" head="Recover password" body="recover_message.tpl">
<widget target="help" mode="contactus" template="common/dialog.tpl" body="contactus.tpl" head="Contact us">
<widget target="help" mode="contactusMessage" template="common/dialog.tpl" body="contactus_message.tpl" head="Message is sent">
<widget target="main" template="pages.tpl">

<widget module="Bestsellers" target="main,category" class="CBestsellers" template="common/dialog.tpl" body="modules/Bestsellers/bestsellers.tpl" head="Bestsellers" visible="{!config.Bestsellers.bestsellers_menu}">

<widget module="FeaturedProducts" target="main,category" template="common/dialog.tpl" body="{config.FeaturedProducts.featured_products_look}" head="Featured products" visible="{category.featuredProducts&!page}">

<widget module="Promotion" target="checkout" mode="bonusList" template="common/dialog.tpl" body="modules/Promotion/bonus_list.tpl" head="You qualify for a special offer">
<widget module="Promotion" target="checkout" template="common/dialog.tpl" body="modules/Promotion/coupon_failed.tpl" head="The discount coupon cannot be used" mode="couponFailed">
<widget module="Promotion" target="cart" template="common/dialog.tpl" body="modules/Promotion/discount_coupon.tpl" head="Discount coupon" visible="{showDCForm}">
<widget module="GiftCertificates" target="add_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/add_gift_certificate.tpl" head="Add gift certificate">
<widget module="GiftCertificates" target="gift_certificate_ecards" template="common/dialog.tpl" body="modules/GiftCertificates/select_ecard.tpl" head="Select e-Card">
<widget module="GiftCertificates" target="check_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/check_gift_certificate.tpl" head="Verify gift certificate">
<widget module="GiftCertificates" target="gift_certificate_info" template="common/dialog.tpl" body="modules/GiftCertificates/gift_certificate_info.tpl" head="Gift certificate">
<widget module="WholesaleTrading" mode="add_error" template="common/dialog.tpl" body="modules/WholesaleTrading/add_error.tpl" head="Product can not be added">
<widget module="WholesaleTrading" target="profile" mode="success" template="modules/WholesaleTrading/membership/register.tpl">
<widget module="Egoods" template="modules/Egoods/main.tpl">
<widget module="Newsletters" template="modules/Newsletters/newsletters.tpl">
<widget module="ProductAdviser" template="modules/ProductAdviser/main.tpl">
<widget module="WishList" target="wishlist,product" mode="MessageSent" template="common/dialog.tpl" body="modules/WishList/message.tpl" head="Message has been sent">
<widget module="WishList" target="wishlist" head="Wish List" template="common/dialog.tpl" body="modules/WishList/wishlist.tpl">
<widget module="WishList" target="product" head="Send to a friend" template="common/dialog.tpl" body="modules/WishList/send_to_friend.tpl">
<widget module="GoogleCheckout" template="common/dialog.tpl" body="modules/GoogleCheckout/google_checkout_dialog.tpl" head="Google Checkout payment module" visible="{target=#googlecheckout#&!valid}" >
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/main.tpl">
<!-- [/center] -->
    </td>
    <td width="15">&nbsp;</td>
    <td width="150" valign="top">
<!-- [right] -->
<widget template="common/sidebar_box.tpl" dir="mini_cart" head="Shopping cart">
<widget template="common/sidebar_box.tpl" dir="login" name="loginWidget" head="Authentication" visible="{!auth.logged}">
<widget template="common/sidebar_box.tpl" dir="profile" name="profileWidget" head="Your profile" visible="{auth.logged}">
<widget template="common/sidebar_box.tpl" dir="authentication" name="authenticationWidget" head="Authentication" visible="{auth.logged}">
<widget module="Newsletters" template="common/sidebar_box.tpl" dir="modules/Newsletters/menu_news" head="News">
<widget module="ProductAdviser" target="main,category,product,cart,RecentlyViewed" class="CNewArrivalsProducts" template="common/sidebar_box.tpl" head="New Arrivals" dir="modules/ProductAdviser/NewArrivals">
<widget module="ProductAdviser" target="main,category,product,cart" class="CRecentliesProducts" template="common/sidebar_box.tpl" head="Recently viewed" dir="modules/ProductAdviser/RecentlyViewed">
<!-- [/right] -->
    </td>
</tr>
</table>
</tr>
</td>
<!-- [/main_view] -->

<TR><TD height="100%"><img src="images/spacer.gif" width=1 height=1 alt=""></TD></TR>

<!-- [bottom] -->
<tr>
<td>
<p align="center">
<widget template="powered_by_litecommerce.tpl">
</p>
</td>
</tr>
<!-- [/bottom] -->

</table>
<!-- [end] -->
<div IF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_footer.tpl">
</div>
</body>
</html>
<!-- [/end] -->
