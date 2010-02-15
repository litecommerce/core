<!-- [center] -->
<widget module="GoogleCheckout" class="XLite_Module_GoogleCheckout_View_GoogleAltCheckout">

<!-- [main] {{{ -->
<widget module="InventoryTracking" target="cart" mode="exceeding" template="common/dialog.tpl" body="modules/InventoryTracking/exceeding.tpl" head="InventoryTracking Notification">
<widget module="WholesaleTrading" mode="update_error" template="common/dialog.tpl" body="modules/WholesaleTrading/update_error.tpl" head="Product quantities not changed">
<widget module="WishList" mode="wishlist" template="common/dialog.tpl" body="modules/WishList/wishlist_note.tpl" head="Wishlist Notification">
<!-- [/main] }}} -->

<!-- [page top] {{{ -->
<widget template="center_top.tpl">
<!-- [/page top] }}} -->

<!-- [catalog] {{{ -->
<widget class="XLite_View_Subcategories" template="common/dialog.tpl" />
<widget class="XLite_View_CategoryProducts" template="common/dialog.tpl">
<widget class="XLite_View_CategoryEmpty" template="common/dialog.tpl">
<!-- [/catalog] }}} -->

<!-- [profile] {{{ -->
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/main.tpl">
<!-- [/profile] }}} -->

<!-- [checkout] {{{ -->
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

<!-- [modules] {{{ -->
<widget module="DetailedImages" target="product" template="common/dialog.tpl" body="modules/DetailedImages/body.tpl" head="Detailed Images" visible="{product.detailedImages}">
<widget module="Bestsellers" class="XLite_Module_Bestsellers_View_Bestsellers" template="common/dialog.tpl" name="bestsellerswidget">
<widget module="FeaturedProducts" target="main,category" mode="" template="common/dialog.tpl" body="{config.FeaturedProducts.featured_products_look}" head="Featured products" visible="{category.featuredProducts&!page}">
<widget module="Promotion" target="checkout" mode="bonusList" template="common/dialog.tpl" body="modules/Promotion/bonus_list.tpl" head="You qualify for a special offer">
<widget module="Promotion" target="checkout" template="common/dialog.tpl" body="modules/Promotion/coupon_failed.tpl" head="The discount coupon cannot be used" mode="couponFailed">
<widget module="Promotion" target="cart" template="common/dialog.tpl" body="modules/Promotion/discount_coupon.tpl" head="Discount coupon" visible="{showDCForm}">
<widget module="WholesaleTrading" mode="add_error" template="common/dialog.tpl" body="modules/WholesaleTrading/add_error.tpl" head="Product can not be added">
<widget module="WholesaleTrading" target="profile" mode="success" template="modules/WholesaleTrading/membership/register.tpl">
<widget module="Egoods" template="modules/Egoods/main.tpl">
<widget module="AdvancedSearch" target="advanced_search" mode="found" template="common/dialog.tpl" body="search_result.tpl" head="Search Result">
<widget module="WishList" target="product" head="Send to a friend" template="common/dialog.tpl" body="modules/WishList/send_to_friend.tpl">
<widget module="ProductAdviser" template="modules/ProductAdviser/main.tpl">
<!-- [/modules] }}} -->

<!-- [/center] -->

