{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Main template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<widget class="\XLite\View\TopMessage" />

<table border=0 width="100%" height="100%">
<tr>
<td valign=top>

<!-- [top] -->
<table cellspacing="0" width="100%" style="background-color: #f4f4f4; border-bottom: 1px solid #e9ecf3;">
<tr>
   <td style="padding: 10px;"><img src="images/logo.png" alt="" /></td>
    <td style="white-space: nowrap;">
      <div style="font-size: 24px;"><span style="color: #2d69ab;">Lite</span><span style="color: #676767;">Commerce</span></div>
      <div>Version: {config.Version.version:h}</div>
    </td>
   <td align="right" valign="top" nowrap="nowrap" width="100%">
   	  <br />
      Welcome <span class="FormButton"><span IF="!auth.profile.billing_address.firstname=##">{auth.profile.billing_address.title} {auth.profile.billing_address.firstname} {auth.profile.billing_address.lastname}</span><span IF="auth.profile.billing_address.firstname=##">{auth.profile.login}</span></span>!<br>
      (<span class="FormButton">{auth.profile.login}</span> logged in)</span>
      <span IF="recentAdmins">,&nbsp;<a href="admin.php?target=recent_login"><u>login history </u></a></span>
      </span>
   	  <br />
      <a href="admin.php?target=login&action=logoff" class="FormButton"><img src="images/go.gif" width="13" height="13" align="absmiddle" /> Log Off</a>
   </td>
</TR>
</TABLE>
<BR>
<!-- [/top] -->

<!-- [main_view] -->
<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
<tr>
  <td nobr width="150" valign="top">
    {displayViewListContent(#menus#)}
  </td>
  <td width="10">&nbsp;</td>
  <td valign="top">
    <noscript>
      <table border=0 width=500 cellpadding=2 cellspacing=0 align=center>
        <tr>
          <td align=center class=ErrorMessage nowrap>This site requires JavaScript to function properly.<br>Please enable JavaScript in your web browser.</td>
        </tr>
      </table>
    </noscript>
<!-- [center] -->

<!-- [breadcrumbs] {{{ -->
<widget class="\XLite\View\Location" />
<!-- [/breadcrumbs] }}} -->

<widget target="access_denied" template="access_denied.tpl" />
<widget class="\XLite\View\AdvBlock" />
<widget template="common/dialog.tpl" head="Customer zone warning" body="customer_zone_warning.tpl" IF="{getCustomerZoneWarning()}" />
<widget target="main" template="common/dialog.tpl" head="Welcome to the Administrator Zone" body="menu.tpl" />
<widget class="\XLite\View\ModulesModify" />
<widget target="module" template="common/dialog.tpl" head="Module {page} settings" body="general_settings.tpl" />

<widget name="categoriesWidget" target="categories" template="common/dialog.tpl" head="Manage categories" body="categories/body.tpl" IF="!mode=#delete#" />
<widget module="FeaturedProducts" template="common/dialog.tpl" head="Featured products" body="modules/FeaturedProducts/featuredProducts.tpl" IF="{namedWidgets.categoriesWidget.visible}" />
<widget target="category" class="\XLite\View\Tabber" body="{getPageTemplate()}" switch="page" />
<widget target="categories" template="common/dialog.tpl" body="categories/delete_confirmation.tpl" head="Confirmation" mode="delete" IF="mode=#delete#" />

<widget target="settings" class="\XLite\View\Tabber" body="general_settings.tpl" switch="page">

<widget template="users/search.tpl" target="users">
<widget target="recent_login" template="common/dialog.tpl" body="recent_login.tpl" head="Login history">

<widget class="\XLite\View\Payment\Methods" />
<widget class="\XLite\View\Payment\Method" />

<widget target="product_list" template="product/product_list_form.tpl">
<widget target="product" class="\XLite\View\Tabber" body="{pageTemplate}" switch="page">

<widget target="extra_fields" template="common/dialog.tpl" body="product/extra_fields_form.tpl" head="Global extra fields">

<widget target="profile" template="common/dialog.tpl" head="Delete profile - Confirmation" body="profile/confirm_delete.tpl" IF="mode=#delete#" />
{*
<widget target="wysiwyg" template="common/dialog.tpl" head="HTML design import/export" body="wysiwyg.tpl" />
*}

<widget class="\XLite\View\Tabs\AdminProfile" />

<widget target="order_list,order,advanced_security" module="AdvancedSecurity" template="modules/AdvancedSecurity/advanced_security.tpl">
<widget module="AntiFraud" target='order' IF="{mode}" mode="{mode}" template="common/dialog.tpl" body="modules/AntiFraud/tracking/message.tpl" head="AntiFraud Service Notification">
<widget module="AntiFraud" target='order' template="common/dialog.tpl" body="modules/AntiFraud/order.tpl" head="AntiFraud Service" IF="{order.details.af_result}">
<widget target="currencies" module="MultiCurrency" template="common/dialog.tpl" body="modules/MultiCurrency/currencies.tpl">
<widget target="advanced_search" module="AdvancedSearch" template="common/dialog.tpl" body="modules/AdvancedSearch/config.tpl">
<widget target="order_list" template="order/search.tpl">

<widget class="\XLite\View\LanguagesModify\Dialog" />

{* Order details page *}
<widget class="\XLite\View\Order\Details\Admin\Model" template="order/order.tpl" />

{*<span IF="!xlite.AOMEnabled">
{if:!xlite.GoogleCheckoutEnabled}
<widget target="order" template="common/dialog.tpl" body="order/order.tpl" head="Order # {order.order_id}">
{else:}
<widget module="AOM" template="modules/AOM/main.tpl">
{end:}*}
</span>
<span IF="xlite.AOMEnabled">
<widget module="AOM" template="modules/AOM/main.tpl">
</span>

<widget class="\XLite\View\Tabs\ShippingSettings">


<widget module="GoogleCheckout" template="modules/GoogleCheckout/main.tpl">

<widget template="stats.tpl">
<widget module="EcommerceReports" template="modules/EcommerceReports/reports.tpl">

<widget target="css_edit" template="common/dialog.tpl" body="css_editor/css_edit.tpl" head="CSS Editor">
<widget target="image_edit" template="common/dialog.tpl" body="image_editor/edit.tpl" head="Image Editor">
{*
<widget target="change_skin" template="common/dialog.tpl" body="change_skin.tpl" head="Change Current Skin">
*}
<widget target="countries" template="common/dialog.tpl" body="countries.tpl" head="Countries">
<widget target="states" template="common/dialog.tpl" body="states.tpl" head="States">
<widget class="\XLite\View\Tabber" target="taxes" body="{pageTemplate}" switch="page">
<widget class="\XLite\View\Tabber" target="db" body="{pageTemplate}" switch="page">

<widget target="import_users" template="common/dialog.tpl" body="import_users.tpl" head="Import users">
<widget target="import_catalog" class="\XLite\View\Tabber" body="{pageTemplate}" switch="page">
<widget target="export_catalog" class="\XLite\View\Tabber" body="{pageTemplate}" switch="page">
<widget target="update_inventory" class="\XLite\View\Tabber" body="{pageTemplate}" switch="page">

<widget class="\XLite\View\Memberships" />
<widget target="template_editor" class="\XLite\View\Tabber" body="{pageTemplate}" switch="editor">
<widget target="image_files" template="common/dialog.tpl" body="image_files.tpl" head="Image files">

{* Gift Certificates module *}
<widget module="GiftCertificates" target="gift_certificates" template="common/dialog.tpl" body="modules/GiftCertificates/list.tpl" head="Gift certificates" />
<widget module="GiftCertificates" target="add_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/add_gift_certificate.tpl" head="Add gift certificate" />
<widget module="GiftCertificates" target="gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/gift_certificate.tpl" head="Gift certificate" />
{*
<widget module="GiftCertificates" target="gift_certificate_ecards" template="common/dialog.tpl" body="modules/GiftCertificates/ecards.tpl" head="Gift certificate e-Cards" />
<widget module="GiftCertificates" target="gift_certificate_ecard" template="common/dialog.tpl" body="modules/GiftCertificates/ecard.tpl" head="Gift certificate e-Card" />
<widget module="GiftCertificates" target="gift_certificate_select_ecard" template="common/dialog.tpl" body="modules/GiftCertificates/select_ecard.tpl" head="Select e-Card" />
*}

<widget module="Promotion" target="SpecialOffers" template="common/dialog.tpl" body="modules/Promotion/special_offers.tpl" head="Special Offers">
<widget module="Promotion" target="SpecialOffer" mode="" template="common/dialog.tpl" body="modules/Promotion/special_offer1.tpl" head="Special Offer Type">
<widget module="Promotion" target="SpecialOffer" mode="details" template="common/dialog.tpl" body="modules/Promotion/special_offer2.tpl" head="Special Offer Details">
<widget module="Promotion" target="DiscountCoupons" template="common/dialog.tpl" body="modules/Promotion/coupons.tpl" head="Discount coupons">
<widget module="Promotion" template="modules/Promotion/main.tpl">

<widget module="USPS" target="usps" class="\XLite\View\Tabber" body="{pageTemplate}" switch="target">
<widget module="USPS" target="usps"  template="common/dialog.tpl" body="modules/USPS/test.tpl" head="USPS Live Test">
<widget module="Intershipper" target="intershipper" class="\XLite\View\Tabber" body="{pageTemplate}" switch="target">
<widget module="Intershipper" target="intershipper"  template="common/dialog.tpl" body="modules/Intershipper/test.tpl" head="Intershipper Live Test">
<widget module="CanadaPost" target="cps" class="\XLite\View\Tabber" body="{pageTemplate}" switch="target">
<widget module="CanadaPost" target="cps" template="common/dialog.tpl" body="modules/CanadaPost/test.tpl" head="Canada Post Live Test">
<widget module="XCartImport" target="xcart_import" template="common/dialog.tpl" body="modules/XCartImport/dialog.tpl" head="Import X-Cart data">
<widget module="Affiliate" template="modules/Affiliate/main.tpl">
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/main.tpl">
<widget module="ProductAdviser" template="modules/ProductAdviser/main.tpl">
<widget module="WishList" target="wishlists" template="modules/WishList/wishlists.tpl" head="Wish Lists">
<widget module="WishList" target="wishlist" template="common/dialog.tpl" body="modules/WishList/wishlist.tpl" head="Wish List">
<widget module="WholesaleTrading" template="modules/WholesaleTrading/main.tpl"> 
{displayViewListContent(#admin.center#)}
<!-- [/center] -->
    </td>
    <td width="10">&nbsp;</td>
</table>
<!-- [/main_view] -->

</td>
</tr>

<!-- align code -->
<script type="text/javascript">
<!--
if (navigator.appName.indexOf('Microsoft') >= 0) {
  document.write('<TR><TD height="100%"><img src="images/spacer.gif" width=1 height=1></TD></TR>');
} else {
  document.write('<TR><TD><img src="images/spacer.gif" width=1 height=1></TD></TR>');
} 
--> 
</script>

<tr>
<td align="center">
<!-- [bottom] -->
<table WIDTH="100%" BORDER=0 CELLPADDING=3 CELLSPACING=0>
<tr>
<td bgcolor=#E0E0E0 HEIGHT=15 align=left>
<widget class="\XLite\View\PoweredBy" />
</td>
<td bgcolor=#E0E0E0 HEIGHT=15 align=right>
<font color="#8A8A8A">Copyright &copy; {config.Company.start_year} {config.Company.company_name}</font>
&nbsp;</td>
</tr>
</table>
<!-- [/bottom] -->

</td>
</tr>
</table>

