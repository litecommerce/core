{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Center column
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="admin.main.page.content.center", weight="10")
 *}
<widget template="noscript.tpl" />

<h1 class="title" id="page-title" IF="isTitleVisible()&getTitle()">{t(getTitle())}</h1>

<widget target="access_denied" template="access_denied.tpl" />

{*** TODO: will be moved to the dashboard side bar
<widget template="common/dialog.tpl" head="Customer zone warning" body="customer_zone_warning.tpl" IF="{getCustomerZoneWarning()}" />
***}

<widget name="categoriesWidget" target="categories" template="common/dialog.tpl" body="categories/body.tpl" IF="!mode=#delete#" />
<widget module="CDev\FeaturedProducts" template="common/dialog.tpl" head="Featured products" body="modules/CDev/FeaturedProducts/featured_products.tpl" IF="{namedWidgets.categoriesWidget.visible}" />

{* Some bug in Flexy *}
<widget target="category" class="\XLite\View\Tabber" body="{getPageTemplate()}" switch="page" />

<widget target="recent_login" template="common/dialog.tpl" body="recent_login.tpl" />

<widget target="product_list" template="product/product_list_form.tpl">

<widget target="product" class="\XLite\View\Tabber" body="{pageTemplate}" switch="page" />
<widget target="add_product" class="\XLite\View\Tabber" body="{pageTemplate}" switch="page" />

<widget target="profile" template="common/dialog.tpl" head="Delete profile - Confirmation" body="profile/confirm_delete.tpl" IF="mode=#delete#" />

<widget target="order" class="\XLite\View\Tabber" body="{pageTemplate}" switch="page" />

<widget target="countries" template="common/dialog.tpl" body="countries.tpl">

<widget target="update_inventory" class="\XLite\View\Tabber" body="{pageTemplate}" switch="page">

<list name="admin.center" />
