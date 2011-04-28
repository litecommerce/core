{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Center column
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<widget template="noscript.tpl" />

<widget class="\XLite\View\Location" />

<h1 class="title" id="page-title" IF="isTitleVisible()&getTitle()">{getTitle():h}</h1>

<widget target="access_denied" template="access_denied.tpl" />

{*** TODO: will be moved to the dashboard side bar
<widget template="common/dialog.tpl" head="Customer zone warning" body="customer_zone_warning.tpl" IF="{getCustomerZoneWarning()}" />
***}

<widget target="main" template="common/dialog.tpl" body="menu.tpl" />
<widget target="module" template="common/dialog.tpl" body="general_settings.tpl" />

<widget name="categoriesWidget" target="categories" template="common/dialog.tpl" body="categories/body.tpl" IF="!mode=#delete#" />
<widget module="CDev\FeaturedProducts" template="common/dialog.tpl" head="Featured products" body="modules/CDev/FeaturedProducts/featured_products.tpl" IF="{namedWidgets.categoriesWidget.visible}" />

{* Some bug in Flexy *}
<widget target="category" class="\XLite\View\Tabber" body="{getPageTemplate()}" switch="page" IF="getCategory()" />
<widget target="category" class="\XLite\View\Tabber" body="{getPageTemplate()}" switch="page" IF="!getCategory()" />

<widget target="settings" class="\XLite\View\Tabber" body="general_settings.tpl" switch="page" />

<widget template="users/search.tpl" target="users">

<widget target="recent_login" template="common/dialog.tpl" body="recent_login.tpl" />

<widget target="product_list" template="product/product_list_form.tpl">

<widget target="product" class="\XLite\View\Tabber" body="{pageTemplate}" switch="page" />
<widget target="add_product" class="\XLite\View\Tabber" body="{pageTemplate}" switch="page" />

<widget target="profile" template="common/dialog.tpl" head="Delete profile - Confirmation" body="profile/confirm_delete.tpl" IF="mode=#delete#" />

<widget class="\XLite\View\Order\Details\Admin\Model" template="order/order.tpl" />

<widget target="countries" template="common/dialog.tpl" body="countries.tpl" head="Countries">

<widget target="update_inventory" class="\XLite\View\Tabber" body="{pageTemplate}" switch="page">

{displayViewListContent(#admin.center#)}
