<?php

$source = strReplace('<meta IF="category" name="keywords" content="{category.meta_tags:h}"></meta>', '', $source, __FILE__, __LINE__);
$source = strReplace('<meta IF="product" name="keywords" content="{product.meta_tags:h}"></meta>', '<meta IF="keywords" name="keywords" content="{keywords:r}"></meta>', $source, __FILE__, __LINE__);
$source = strReplace('{product_search.display()}', '<widget template="search_products.tpl">', $source, __FILE__, __LINE__);

$source = strReplace('{sidebar_categories.display()}', '<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories">', $source, __FILE__, __LINE__);
$source = strReplace('{sidebar_gift_certificates.display()}', '<widget module="GiftCertificates" template="common/sidebar_box.tpl" head="Gift certificates" dir="modules/GiftCertificates/menu">', $source, __FILE__, __LINE__);
$source = strReplace('{sidebar_bestsellers.display()}', '<widget module="Bestsellers" class="CBestsellers" template="common/sidebar_box.tpl" head="Bestsellers" dir="modules/Bestsellers/menu" visible="{config.Bestsellers.bestsellers_menu}">', $source, __FILE__, __LINE__);
$source = strReplace('{sidebar_help.display()}', '<widget template="common/sidebar_box.tpl" head="Help" dir="help">', $source, __FILE__, __LINE__);

$source = strReplace('{greet_visitor.display()}', '<widget module="GreetVisitor" target="main" mode="" template="modules/GreetVisitor/greet_visitor.tpl" visible="{greetVisitor&!page}">', $source, __FILE__, __LINE__);
$source = strReplace('{welcome.display()}', '<widget target="main" mode="" template="welcome.tpl" name="welcomeWidget" visible="{!page}">', $source, __FILE__, __LINE__);
$source = strReplace('{location.display()}', '<widget template="location.tpl" name="locationWidget">', $source, __FILE__, __LINE__);
$source = strReplace('{access_denied.display()}', '<widget target="main" mode="accessDenied" template="access_denied.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{authentication.display(#Authentication#)}', '<widget target="login" template="common/dialog.tpl" body="authentication_error.tpl" head="Authentication">', $source, __FILE__, __LINE__);
$source = strReplace('{category_description.display()}', '<widget target="category" template="category_description.tpl" visible="{category.description}">', $source, __FILE__, __LINE__);
$source = strReplace('{bestsellers.display(#Bestsellers#)}', '<widget module="Bestsellers" target="main,category" class="CBestsellers" template="common/dialog.tpl" body="modules/Bestsellers/bestsellers.tpl" head="Bestsellers" visible="{!config.Bestsellers.bestsellers_menu}">', $source, __FILE__, __LINE__);
$source = strReplace('{categories.display(#%s - Subcategories#)}', '<widget target="category" template="common/dialog.tpl" body="{config.General.subcategories_look}" head="{category.name} - Subcategories" visible="{category.subcategories}">', $source, __FILE__, __LINE__);
$source = strReplace('{products.display(#%s - Product list#)}', '<widget target="category" template="common/dialog.tpl" body="category_products.tpl" head="{category.name} - Product list" visible="{category.products}">'."\n".'<widget target="category" template="common/dialog.tpl" body="category_empty.tpl" head="{category.name} - Product list" visible="{category.empty}">', $source, __FILE__, __LINE__);
$source = strReplace('{product.display(#%s#)}', '<widget target="product" template="common/dialog.tpl" body="product_details.tpl" head="{product.name}">', $source, __FILE__, __LINE__);
$source = strReplace('{cart.display(#Shopping cart#)}', '<widget target="cart" template="common/dialog.tpl" body="shopping_cart/body.tpl" head="Shopping cart">', $source, __FILE__, __LINE__);
$source = strReplace('{register.display(#New member#)}', '<widget target="profile" mode="register" class="CRegisterForm" template="common/dialog.tpl" head="New member" body="register_form.tpl" name="registerForm">', $source, __FILE__, __LINE__);
$source = strReplace('{register_success.display(#Registration success#)}', '<widget target="profile" mode="success" template="common/dialog.tpl" head="Registration success" body="register_success.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{profile.display(#Modify profile#)}', '<widget target="profile" mode="modify" class="CRegisterForm" template="common/dialog.tpl" head="Modify profile" body="profile.tpl" name="profileForm">', $source, __FILE__, __LINE__);
$source = strReplace('{delete_profile.display(#Delete profile - Confirmation#)}', '<widget target="profile" mode="delete" template="common/dialog.tpl" head="Delete profile - Confirmation" body="delete_profile.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{checkout_registration.display(#Checkout: registration#,#New member#)}', '<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart">'."\n".'<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="New member" name="registerForm" allowAnonymous>', $source, __FILE__, __LINE__);
$source = strReplace('{checkout_payment.display(#Checkout: payment method#,#Payment method#)}', '<widget target="checkout" mode="paymentMethod" template="common/dialog.tpl" body="payment_method.tpl" head="Payment method">', $source, __FILE__, __LINE__);
$source = strReplace('{checkout_details.display(#Checkout: payment details#)}', '<widget target="checkout" mode="details" template="common/dialog.tpl" body="checkout/details_dialog.tpl" head="{cart.paymentMethod.name}">', $source, __FILE__, __LINE__);
$source = strReplace('{checkout_success.display(#Order processed#,#Invoice#)}', '<widget target="checkoutSuccess" template="checkout/success.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{checkout_not_allowed.display()}', '<widget target="checkout" mode="notAllowed" template="common/dialog.tpl" body="checkout/not_allowed.tpl" head="Checkout not allowed">', $source, __FILE__, __LINE__);
$source = strReplace('{checkout_no_shipping.display()}', '<widget target="checkout" mode="noShipping" template="common/dialog.tpl" body="checkout/no_shipping.tpl" head="No shipping method available">', $source, __FILE__, __LINE__);
$source = strReplace('{checkout_error.display(#Checkout error#)}', '<widget target="checkout" mode="error" template="common/dialog.tpl" body="checkout/failure.tpl" head="Checkout error">', $source, __FILE__, __LINE__);
$source = strReplace('{order_list.display(#Search orders#,#Search result#)}', '<widget target="order_list" template="order/search.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{order.display(#Order%s %d#)}', '<widget target="order" template="common/dialog.tpl" body="common/invoice.tpl" head="Order # {order.order_id}">', $source, __FILE__, __LINE__);
$source = strReplace('{detailed_images.display(#Detailed Images#)}', '<widget module="DetailedImages" target="product" template="common/dialog.tpl" body="modules/DetailedImages/body.tpl" head="Detailed Images" visible="{product.detailedImages}">', $source, __FILE__, __LINE__);
$source = strReplace('{search_result.display(#Search Result#)}', '<widget target="search" template="common/dialog.tpl" body="search_result.tpl" head="Search Result">', $source, __FILE__, __LINE__);
$source = strReplace('{conditions.display(#Terms & Conditions#)}', '<widget target="help" mode="terms_conditions" template="common/dialog.tpl" body="terms_conditions.tpl" head="Terms & Conditions">', $source, __FILE__, __LINE__);
$source = strReplace('{privacy_statement.display(#Privacy statement#)}', '<widget target="help" mode="privacy_statement" template="common/dialog.tpl" body="privacy_statement.tpl" head="Privacy statement">', $source, __FILE__, __LINE__);
$source = strReplace('{recover_password.display(#Forgot password?#)}', '<widget target="recover_password" mode="" template="common/dialog.tpl" head="Forgot password?" body="recover_password.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{recover_password_message.display(#Recover password#)}', '<widget target="recover_password" mode="recoverMessage" template="common/dialog.tpl" head="Recover password" body="recover_message.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{contactus.display(#Contact us#)}', '<widget target="help" mode="contactus" template="common/dialog.tpl" body="contactus.tpl" head="Contact us">', $source, __FILE__, __LINE__);
$source = strReplace('{contactus_message.display(#Contact us#)}', '<widget target="help" mode="contactusMessage" template="common/dialog.tpl" body="contactus_message.tpl" head="Message is sent">', $source, __FILE__, __LINE__);
$source = strReplace('{extra_pages.display()}', '<widget target="main" template="pages.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{featuredProducts.display(#Featured products#)}', '<widget module="FeaturedProducts" target="main,category" template="common/dialog.tpl" body="{config.FeaturedProducts.featured_products_look}" head="Featured products" visible="{category.featuredProducts&!page}">', $source, __FILE__, __LINE__);
$source = strReplace('{checkoutCouponFailed.display(#The discount coupon cannot be used#)}', '<widget module="Promotion" target="checkout" template="common/dialog.tpl" body="modules/Promotion/coupon_failed.tpl" head="The discount coupon cannot be used" mode="couponFailed">', $source, __FILE__, __LINE__);
$source = strReplace('{checkoutBonusList.display(#You have got bonus!#)}', '<widget module="Promotion" target="checkout" mode="bonusList" template="common/dialog.tpl" body="modules/Promotion/bonus_list.tpl" head="You have got bonus!">', $source, __FILE__, __LINE__);
$source = strReplace('{discountCoupon.display(#Discount coupon#)}', '<widget module="Promotion" target="cart" template="common/dialog.tpl" body="modules/Promotion/discount_coupon.tpl" head="Discount coupon" visible="{showDCForm}">', $source, __FILE__, __LINE__);
$source = strReplace('{add_gift_certificate.display(#Add gift certificate#)}', '<widget module="GiftCertificates" target="add_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/add_gift_certificate.tpl" head="Add gift certificate">', $source, __FILE__, __LINE__);
$source = strReplace('{ECardSelect.display(#Select e-Card#)}', '<widget module="GiftCertificates" target="gift_certificate_ecards" class="CECardSelect" head="Select e-Card" name="ecardSelectForm">', $source, __FILE__, __LINE__);
$source = strReplace('{check_gift_certificate.display(#Verify gift certificate#)}', '<widget module="GiftCertificates" target="check_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/check_gift_certificate.tpl" head="Verify gift certificate">', $source, __FILE__, __LINE__);
$source = strReplace('{mini_cart.display()}', '<widget template="common/sidebar_box.tpl" dir="mini_cart" head="Shopping cart">', $source, __FILE__, __LINE__);
$source = strReplace('{sidebar_login.display()}', '<widget template="common/sidebar_box.tpl" dir="login" name="loginWidget" head="Authentication" visible="{!auth.logged}">', $source, __FILE__, __LINE__);
$source = strReplace('{sidebar_authentication.display()}', '<widget template="common/sidebar_box.tpl" dir="authentication" name="authenticationWidget" head="Authentication" visible="{auth.logged}">', $source, __FILE__, __LINE__);
$source = strReplace('{sidebar_profile.display()}', '<widget template="common/sidebar_box.tpl" dir="profile" name="profileWidget" head="Your profile" visible="{auth.logged}">', $source, __FILE__, __LINE__);
$source = strReplace('{powered.display()}', '', $source, __FILE__, __LINE__);
$source = strReplace('{displayExtraWidgets()}', '', $source, __FILE__, __LINE__);
$source = strReplace('{profiler.display()}', '', $source, __FILE__, __LINE__);

?>
