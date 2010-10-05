{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<!-- [center] -->
<widget module="GoogleCheckout" class="\XLite\Module\GoogleCheckout\View\GoogleAltCheckout">

<!-- [main] {{{ -->
<widget module="InventoryTracking" target="cart" mode="exceeding" template="common/dialog.tpl" body="modules/InventoryTracking/exceeding.tpl" head="InventoryTracking Notification">
<widget module="WholesaleTrading" mode="update_error" template="common/dialog.tpl" body="modules/WholesaleTrading/update_error.tpl" head="Product quantities not changed">
<widget module="WishList" mode="wishlist" template="common/dialog.tpl" body="modules/WishList/wishlist_note.tpl" head="Wishlist Notification">
<!-- [/main] }}} -->

<!-- [breadcrumbs] {{{ -->
<widget class="\XLite\View\Location" />
<!-- [/breadcrumbs] }}} -->

<!-- [page top] {{{ -->
<widget template="center_top.tpl" />
<!-- [/page top] }}} -->

<!-- [catalog] {{{ -->
<widget class="\XLite\View\Subcategories" />
<widget class="\XLite\View\ItemsList\Product\Customer\Category" />
<widget class="\XLite\View\CategoryEmpty" />
<!-- [/catalog] }}} -->

<!-- [profile] {{{ -->
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/main.tpl">
<!-- [/profile] }}} -->

<!-- [modules] {{{ -->
<widget module="DetailedImages" target="product" template="common/dialog.tpl" body="modules/DetailedImages/body.tpl" head="Detailed Images" IF="{product.detailedImages}">
<widget module="FeaturedProducts" class="\XLite\Module\FeaturedProducts\View\FeaturedProducts" template="common/dialog.tpl">
<widget module="Promotion" target="checkout" mode="bonusList" template="common/dialog.tpl" body="modules/Promotion/bonus_list.tpl" head="You qualify for a special offer">
<widget module="Promotion" target="checkout" template="common/dialog.tpl" body="modules/Promotion/coupon_failed.tpl" head="The discount coupon cannot be used" mode="couponFailed">
<widget module="Promotion" target="cart" template="common/dialog.tpl" body="modules/Promotion/discount_coupon.tpl" head="Discount coupon" IF="{showDCForm}">
<widget module="WholesaleTrading" mode="add_error" template="common/dialog.tpl" body="modules/WholesaleTrading/add_error.tpl" head="Product can not be added">
<widget module="WholesaleTrading" target="profile" mode="success" template="modules/WholesaleTrading/membership/register.tpl">
<widget module="Egoods" template="modules/Egoods/main.tpl">
<widget module="ProductAdviser" template="modules/ProductAdviser/main.tpl">
<!-- [/modules] }}} -->

<!-- [/center] -->
