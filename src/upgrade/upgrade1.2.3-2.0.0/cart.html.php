<?php

$search =<<<EOT
{welcome.display()}
{access_denied.display()}
{authentication.display(#Authentication#)}
{category_description.display()}
{categories.display(#%s - Subcategories#)}
{products.display(#%s - Product list#)}
{product.display(#%s#)}
{cart.display(#Shopping cart#)}
{register.display(#New member#)}
{register_success.display(#Registration success#)}
{profile.display(#Modify profile#)}
{delete_profile.display(#Delete profile - Confirmation#)}
{checkout_registration.display(#Checkout: registration#,#New member#)}
{checkout_payment.display(#Checkout: payment method#,#Payment method#)}
{checkout_details.display(#Checkout: payment details#)}
{checkout_success.display(#Order processed#,#Invoice#)}
{order_list.display(#Search orders#,#Search result#)}
{order.display(#Order%s %d#)}
{checkout_not_allowed.display()}
{checkout_no_shipping.display()}
{checkout_error.display(#Checkout error#)}
{bestsellers.display()}
{featured_products.display()}
{detailed_images.display(#Detailed Images#)}
{search_result.display(#Search Result#)}
{conditions.display(#Terms & Conditions#)}
{privacy_statement.display(#Privacy statement#)}
{recover_password.display(#Forgot password?#)}
{recover_password_message.display(#Recover password#)}
{contactus.display(#Contact us#)}
{contactus_message.display(#Contact us#)}
{extra_pages.display()}
{displayExtraWidgets()}
EOT;

$replace =<<<EOT
<widget target="main" mode="" template="welcome.tpl" name="welcomeWidget" visible="{!page}">
<widget target="main" mode="accessDenied" template="access_denied.tpl">
<widget target="login" template="common/dialog.tpl" body="authentication_error.tpl" head="Authentication">
<widget target="category" template="category_description.tpl" visible="{category.description}">
<widget target="category" template="common/dialog.tpl" body="{config.General.subcategories_look}" head="{category.name} - Subcategories" visible="{category.subcategories}">
<widget target="category" template="common/dialog.tpl" body="category_products.tpl" head="{category.name} - Product list" visible="{category.products}">
<widget target="category" template="common/dialog.tpl" body="category_empty.tpl" head="{category.name} - Product list" visible="{category.empty}">
<widget target="product" template="common/dialog.tpl" body="product_details.tpl" head="{product.name}">
<widget target="cart" template="common/dialog.tpl" body="shopping_cart/body.tpl" head="Shopping cart">
<widget target="profile" mode="register" class="CRegisterForm" template="common/dialog.tpl" head="New member" body="register_form.tpl" name="registerForm">
<widget target="profile" mode="success" template="common/dialog.tpl" head="Registration success" body="register_success.tpl">
<widget target="profile" mode="modify" class="CRegisterForm" template="common/dialog.tpl" head="Modify profile" body="profile.tpl" name="profileForm">
<widget target="profile" mode="delete" template="common/dialog.tpl" head="Delete profile - Confirmation" body="delete_profile.tpl">
<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart">
<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="New member" name="registerForm" allowAnonymous>
<widget target="checkout" mode="paymentMethod" template="common/dialog.tpl" body="payment_method.tpl" head="Payment method">
<widget target="checkout" mode="details" template="common/dialog.tpl" body="checkout/details_dialog.tpl" head="{cart.paymentMethod.name}">
<widget target="checkoutSuccess" template="checkout/success.tpl">
<widget target="checkout" mode="notAllowed" template="common/dialog.tpl" body="checkout/not_allowed.tpl" head="Checkout not allowed">
<widget target="checkout" mode="noShipping" template="common/dialog.tpl" body="checkout/no_shipping.tpl" head="No shipping method available">
<widget target="checkout" mode="error" template="common/dialog.tpl" body="checkout/failure.tpl" head="Checkout error">
<widget target="order_list" template="order/search.tpl">
<widget target="order" template="common/dialog.tpl" body="common/invoice.tpl" head="Order # {order.order_id}">
<widget module="DetailedImages" target="product" template="common/dialog.tpl" body="modules/DetailedImages/body.tpl" head="Detailed Images" visible="{product.detailedImages}">
<widget target="search" template="common/dialog.tpl" body="search_result.tpl" head="Search Result">
<widget target="help" mode="terms_conditions" template="common/dialog.tpl" body="terms_conditions.tpl" head="Terms & Conditions">
<widget target="help" mode="privacy_statement" template="common/dialog.tpl" body="privacy_statement.tpl" head="Privacy statement">
<widget target="recover_password" mode="" template="common/dialog.tpl" head="Forgot password?" body="recover_password.tpl">
<widget target="recover_password" mode="recoverMessage" template="common/dialog.tpl" head="Recover password" body="recover_message.tpl">
<widget target="help" mode="contactus" template="common/dialog.tpl" body="contactus.tpl" head="Contact us">
<widget target="help" mode="contactusMessage" template="common/dialog.tpl" body="contactus_message.tpl" head="Message is sent">
<widget target="main" template="pages.tpl">

<widget module="Bestsellers" target="main,category" class="CBestsellers" template="common/dialog.tpl" body="modules/Bestsellers/bestsellers.tpl" head="Bestsellers" visible="{!config.Bestsellers.bestsellers_menu}">

<widget module="FeaturedProducts" target="main,category" template="common/dialog.tpl" body="{config.FeaturedProducts.featured_products_look}" head="Featured products" visible="{category.featuredProducts&!page}">

<widget module="Promotion" target="checkout" mode="bonusList" template="common/dialog.tpl" body="modules/Promotion/bonus_list.tpl" head="You have got bonus!">
<widget module="Promotion" target="checkout" template="common/dialog.tpl" body="modules/Promotion/coupon_failed.tpl" head="The discount coupon cannot be used" mode="couponFailed">
<widget module="Promotion" target="cart" template="common/dialog.tpl" body="modules/Promotion/discount_coupon.tpl" head="Discount coupon" visible="{showDCForm}">
<widget module="GiftCertificates" target="add_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/add_gift_certificate.tpl" head="Add gift certificate">
<widget module="GiftCertificates" target="gift_certificate_ecards" class="CECardSelect" head="Select e-Card" name="ecardSelectForm">
<widget module="GiftCertificates" target="check_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/check_gift_certificate.tpl" head="Verify gift certificate">
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

?>
