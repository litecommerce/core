<!-- [center] -->
<widget module="GoogleCheckout" class="XLite_Module_GoogleCheckout_View_GoogleAltCheckout">

<!-- [main] {{{ -->
<widget module="InventoryTracking" target="cart" mode="exceeding" template="common/dialog.tpl" body="modules/InventoryTracking/exceeding.tpl" head="InventoryTracking Notification">
<widget module="WholesaleTrading" mode="update_error" template="common/dialog.tpl" body="modules/WholesaleTrading/update_error.tpl" head="Product quantities not changed">
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
<widget target="profile" mode="register" class="XLite_View_RegisterForm" template="common/dialog.tpl" head="New customer" body="register_form.tpl" name="registerForm" IF="!showAV"/>
<widget target="profile" mode="success" template="common/dialog.tpl" head="Registration complete" body="register_success.tpl">
<widget target="profile" mode="modify" class="XLite_View_RegisterForm" template="common/dialog.tpl" head="Modify profile" body="profile.tpl" name="profileForm" IF="!showAV"/>
<widget target="profile" mode="delete" template="common/dialog.tpl" head="Delete profile - Confirmation" body="delete_profile.tpl">
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/main.tpl">
<!-- [/profile] }}} -->

<!-- [checkout] {{{ -->
<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart" IF="!showAV"/>
<widget module="PayPalPro" target="checkout" mode="register" template="common/dialog.tpl" body="modules/PayPalPro/retrieve_profile.tpl" head="Make checkout easier with PayPal Website Pro" visible="{xlite.PayPalProExpressEnabled}">
<widget target="checkout" mode="register" class="XLite_View_RegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="Customer Information" name="registerForm" allowAnonymous="{config.General.enable_anon_checkout}" IF="!showAV"/>
<widget target="checkout" mode="paymentMethod" template="common/dialog.tpl" body="payment_method.tpl" head="Payment method">
<widget target="checkout" mode="details" template="common/dialog.tpl" body="checkout/details_dialog.tpl" head="{cart.paymentMethod.name}">
<widget target="checkoutSuccess" template="checkout/success.tpl">
<widget target="checkout" mode="notAllowed" template="common/dialog.tpl" body="checkout/not_allowed.tpl" head="Checkout not allowed">
<widget target="checkout" mode="noShipping" template="common/dialog.tpl" body="checkout/no_shipping.tpl" head="No shipping method available">
<widget target="checkout" mode="noPayment" template="common/dialog.tpl" body="checkout/no_payment.tpl" head="No payment method available">
<widget target="checkout" mode="error" template="common/dialog.tpl" body="checkout/failure.tpl" head="Checkout error">
<widget module="GoogleCheckout" template="common/dialog.tpl" body="modules/GoogleCheckout/google_checkout_dialog.tpl" head="Google Checkout payment module" visible="{target=#googlecheckout#&!valid}" >
<!-- [/checkout] }}} -->

<!-- [order] {{{ -->
<widget target="order_list" template="order/search.tpl">
<widget target="order" template="common/dialog.tpl" body="order/order.tpl" head="Order # {order.order_id}">
<!-- [/order] }}} -->

<!-- [modules] {{{ -->
<widget module="DetailedImages" target="product" template="common/dialog.tpl" body="modules/DetailedImages/body.tpl" head="Detailed Images" visible="{product.detailedImages}">
<widget module="Bestsellers" target="main,category" mode="" class="XLite_Module_Bestsellers_View_Bestsellers" template="common/dialog.tpl" body="modules/Bestsellers/bestsellers.tpl" head="Bestsellers" visible="{!config.Bestsellers.bestsellers_menu}" name="bestsellerswidget">
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

