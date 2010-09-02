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
<font class="NavigationPath">
<a href="admin.php" class="NavigationPath">Admin menu</a>
<span IF="target=#access_denied#">&nbsp;::&nbsp;<font class="NavigationPath">ACCESS DENIED</font></span>
<span IF="target=#categories#">&nbsp;::&nbsp;<a href="admin.php?target=categories&category_id=0" class="NavigationPath">Categories</a></span>
<span IF="target=#category#">&nbsp;::&nbsp;<a href="admin.php?target=categories&category_id=0" class="NavigationPath">Categories</a></span>
<span FOREACH="locationPath,cname,curl">&nbsp;::&nbsp;<a href="{curl:r}" class="NavigationPath">{cname}</a></span>
<span IF="target=#profile#&getRequestParamValue(#mode#)=#modify#">&nbsp;::&nbsp;<a href="admin.php?target=users" class="NavigationPath">Users</a>&nbsp;::&nbsp;Modify profile</span>
<span IF="target=#profile#&getRequestParamValue(#mode#)=##">&nbsp;::&nbsp;<a href="admin.php?target=users" class="NavigationPath">Users</a>&nbsp;::&nbsp;Modify profile</span>
<span IF="target=#profile#&getRequestParamValue(#mode#)=#delete#">&nbsp;::&nbsp;Delete profile</span>
<span IF="target=#modules#">&nbsp;::&nbsp;<a href="admin.php?target=modules" class="NavigationPath">Modules</a></span>
<span IF="target=#module#">&nbsp;::&nbsp;<a href="admin.php?target=modules" class="NavigationPath">Modules</a>&nbsp;::&nbsp;{page} settings</span>
<span IF="target=#settings#">&nbsp;::&nbsp;<a href="admin.php?target=settings" class="NavigationPath">General Settings</a></span>
<span IF="target=#users#">&nbsp;::&nbsp;<a href="admin.php?target=users" class="NavigationPath">Users</a></span>
<span IF="target=#extra_fields#">&nbsp;::&nbsp;Global extra fields</span>
<span IF="target=#product#">&nbsp;::&nbsp;Add new product</span>
<span IF="target=#product#">&nbsp;::&nbsp;<a href="{backUrl:r}" class="NavigationPath">Product list</a>&nbsp;::&nbsp;Edit product "{product.name:h}"</span>
<span IF="target=#catalog#">&nbsp;::&nbsp;HTML Catalog</span>
<span IF="target=#autoupdate_catalog#">&nbsp;::&nbsp;Confirm HTML catalog update</span>
<span IF="target=#product_list#">&nbsp;::&nbsp;Products</span>
<span IF="target=#global_product_options#">&nbsp;::&nbsp;<a href="admin.php?target=global_product_options" class="NavigationPath">Global product options</a></span>
<span IF="target=#payment_methods#">&nbsp;::&nbsp;<a href="admin.php?target=payment_methods" class="NavigationPath">Payment methods</a></span>
<span IF="target=#profile#&getRequestParamValue(#mode#)=#register#">&nbsp;::&nbsp;<a href="admin.php?target=users" class="NavigationPath">Users</a>&nbsp;::&nbsp;<a href="admin.php?target=profile&mode=register" class="NavigationPath">Add new user</a></span>
<span IF="target=#order_list#">&nbsp;::&nbsp;<a href="admin.php?target=order_list" class="NavigationPath">Search orders</a></span>
<span IF="target=#order#">&nbsp;::&nbsp;<a href="admin.php?target=order_list" class="NavigationPath">Search orders</a>&nbsp;::&nbsp;Order details</span>
<span IF="target=#shipping_settings#">&nbsp;::&nbsp;<a href="admin.php?target=shipping_settings" class="NavigationPath">Shipping settings</a></span>
<span IF="target=#shipping_zones#">&nbsp;::&nbsp;<a href="admin.php?target=shipping_zones&page=zones" class="NavigationPath">Shipping settings :: Shipping zones</a></span>
<span IF="target=#shipping_methods#">&nbsp;::&nbsp;<a href="admin.php?target=shipping_methods" class="NavigationPath">Shipping settings :: Shipping methods</a></span>
<span IF="target=#shipping_rates#">&nbsp;::&nbsp;<a href="admin.php?target=shipping_rates" class="NavigationPath">Shipping settings :: Shipping charges</a></span>
<span IF="target=#payment_method#">&nbsp;::&nbsp;<a href="admin.php?target=payment_methods" class="NavigationPath">Payment methods</a>&nbsp;::&nbsp;{pm.processorName} configuration</span>
</font>
<span IF="target=#countries#">&nbsp;::&nbsp;<a href="admin.php?target=countries" class="NavigationPath">Countries</a></span>
<span IF="target=#states#">&nbsp;::&nbsp;<a href="admin.php?target=states" class="NavigationPath">States</a></span>
<span IF="target=#usps#">&nbsp;::&nbsp;<a href="admin.php?target=usps" class="NavigationPath">Shipping settings :: USPS Settings</a></span>
<span IF="target=#cps#">&nbsp;::&nbsp;<a href="admin.php?target=cps" class="NavigationPath">Shipping settings :: Canada Post Settings</a></span>
<span IF="target=#intershipper#">&nbsp;::&nbsp;<a href="admin.php?target=intershipper" class="NavigationPath">Shipping settings :: Intershipper Settings</a></span>
<span IF="target=#css_edit#">&nbsp;::&nbsp;<a href="admin.php?target=css_edit" class="NavigationPath">CSS Editor</a></span>
<span IF="target=#image_edit#">&nbsp;::&nbsp;<a href="admin.php?target=image_edit" class="NavigationPath">Image Editor</a></span>
<span IF="target=#db#">&nbsp;::&nbsp;<a href="admin.php?target=db" class="NavigationPath">Database Backup/Restore</a></span>
<span IF="target=#update_inventory#">&nbsp;::&nbsp;<a href="admin.php?target=update_inventory" class="NavigationPath">Inventory management</a></span>
<span IF="target=#taxes#">&nbsp;::&nbsp;<a href="admin.php?target=taxes" class="NavigationPath">Taxes</a></span>
<span IF="target=#tax_edit#">&nbsp;::&nbsp;<a href="admin.php?target=taxes" class="NavigationPath">Taxes</a>&nbsp;::&nbsp;Add/edit a tax rate/condition</span>
<span IF="target=#import_export#">&nbsp;::&nbsp;<a href="admin.php?target=import_export" class="NavigationPath">Import/Export</a></span>
<span IF="target=#import_catalog#">&nbsp;::&nbsp;<a href="admin.php?target=import_catalog" class="NavigationPath">Import catalog</a></span>
<span IF="target=#export_catalog#">&nbsp;::&nbsp;<a href="admin.php?target=export_catalog" class="NavigationPath">Export catalog</a></span>
<span IF="target=#memberships#">&nbsp;::&nbsp;<a href="admin.php?target=memberships" class="NavigationPath">Membership levels</a></span>
<span IF="target=#orders_stats#">&nbsp;::&nbsp;<a href="admin.php?target=orders_stats" class="NavigationPath">Orders statistics</a></span>
<span IF="target=#top_sellers#">&nbsp;::&nbsp;<a href="admin.php?target=orders_stats" class="NavigationPath">Top sellers</a></span>
<span IF="target=#searchStat#">&nbsp;::&nbsp;<a href="admin.php?target=searchStat" class="NavigationPath">Search statistics</a></span>
<span IF="target=#import_users#">&nbsp;::&nbsp;<a href="admin.php?target=import_users" class="NavigationPath">Import users</a></span>
<span IF="target=#recent_login#">&nbsp;::&nbsp;<a href="admin.php?target=recent_login" class="NavigationPath">Login history</a></span>
<widget module="AOM" template="modules/AOM/location.tpl">
<span IF="target=#template_editor#" class="NavigationPath">&nbsp;::&nbsp;<a href="admin.php?target=template_editor" class="NavigationPath">Template editor</a>{if:node}&nbsp;::&nbsp;{node}{end:}{if:file.path}&nbsp;::&nbsp;{file.path}{end:}</span>
<span IF="target=#image_files#" class="NavigationPath">&nbsp;::&nbsp;<a href="admin.php?target=image_files" class="NavigationPath">Image files</a></span>
<widget module="WholesaleTrading" template="modules/WholesaleTrading/location.tpl">
<widget module="AustraliaPost" template="modules/AustraliaPost/location.tpl">
<widget module="GiftCertificates" template="modules/GiftCertificates/location.tpl">
<widget module="Promotion" template="modules/Promotion/location.tpl">
<widget module="WishList" template="modules/WishList/location.tpl">
<widget module="MultiCurrency" template="modules/MultiCurrency/location.tpl">
<widget module="AdvancedSearch" template="modules/AdvancedSearch/location.tpl">

<widget module="XCartImport" template="modules/XCartImport/location.tpl">
<widget module="AccountingPackage" template="modules/AccountingPackage/location.tpl">
<widget module="AdvancedSecurity" template="modules/AdvancedSecurity/location.tpl">
<widget module="Affiliate" template="modules/Affiliate/location.tpl">
<widget module="Egoods" template="modules/Egoods/location.tpl">
<widget module="EcommerceReports" template="modules/EcommerceReports/location.tpl">
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/location.tpl">
<widget module="ProductAdviser" template="modules/ProductAdviser/location.tpl">
