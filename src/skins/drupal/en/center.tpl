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
<widget module="CDev\GoogleCheckout" class="\XLite\Module\CDev\GoogleCheckout\View\GoogleAltCheckout">

<!-- [main] {{{ -->
<widget module="CDev\InventoryTracking" target="cart" mode="exceeding" template="common/dialog.tpl" body="modules/CDev/InventoryTracking/exceeding.tpl" head="InventoryTracking Notification">
<widget module="CDev\WholesaleTrading" mode="update_error" template="common/dialog.tpl" body="modules/CDev/WholesaleTrading/update_error.tpl" head="Product quantities not changed">
<widget module="CDev\WishList" mode="wishlist" template="common/dialog.tpl" body="modules/CDev/WishList/wishlist_note.tpl" head="Wishlist Notification">
<!-- [/main] }}} -->

<!-- [breadcrumbs] {{{ -->
<widget class="\XLite\View\Location" />
<!-- [/breadcrumbs] }}} -->

<!-- [page top] {{{ -->
<h1 class="title" id="page-title" IF="isTitleVisible()&getTitle()">{getTitle():h}</h1>
<widget template="center_top.tpl" />
<!-- [/page top] }}} -->

<!-- [catalog] {{{ -->
<widget class="\XLite\View\Subcategories" />
<widget class="\XLite\View\ItemsList\Product\Customer\Category" />
<widget class="\XLite\View\CategoryEmpty" />
<!-- [/catalog] }}} -->

<!-- [profile] {{{ -->
<widget module="CDev\UPSOnlineTools" template="modules/CDev/UPSOnlineTools/main.tpl">
<!-- [/profile] }}} -->

<!-- [modules] {{{ -->
<widget module="CDev\DetailedImages" target="product" template="common/dialog.tpl" body="modules/CDev/DetailedImages/body.tpl" head="Detailed Images" IF="{product.detailedImages}">
{*
<widget module="CDev\Bestsellers" class="\XLite\Module\CDev\Bestsellers\View\Bestsellers" template="common/dialog.tpl" IF="{!config.Bestsellers.bestsellers_menu}" name="bestsellerswidget">
*}
<widget module="CDev\FeaturedProducts" class="\XLite\Module\CDev\FeaturedProducts\View\Customer\FeaturedProducts" template="common/dialog.tpl">
<widget module="CDev\Promotion" target="checkout" mode="bonusList" template="common/dialog.tpl" body="modules/CDev/Promotion/bonus_list.tpl" head="You qualify for a special offer">
<widget module="CDev\Promotion" target="checkout" template="common/dialog.tpl" body="modules/CDev/Promotion/coupon_failed.tpl" head="The discount coupon cannot be used" mode="couponFailed">
<widget module="CDev\Promotion" target="cart" template="common/dialog.tpl" body="modules/CDev/Promotion/discount_coupon.tpl" head="Discount coupon" IF="{showDCForm}">
<widget module="CDev\WholesaleTrading" mode="add_error" template="common/dialog.tpl" body="modules/CDev/WholesaleTrading/add_error.tpl" head="Product can not be added">
<widget module="CDev\WholesaleTrading" target="profile" mode="success" template="modules/CDev/WholesaleTrading/membership/register.tpl">
<widget module="CDev\Egoods" template="modules/CDev/Egoods/main.tpl">
<widget module="CDev\ProductAdviser" template="modules/CDev/ProductAdviser/main.tpl">
<!-- [/modules] }}} -->

<!-- [/center] -->
