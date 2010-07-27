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
<!-- [begin] -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>
        LiteCommerce. Powerful PHP shopping cart software - Partner zone.
        {if:target=#help#} - Help section{end:}
    </title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="skins/{shopLayout.skin}/{shopLayout.locale}/modules/Affiliate/style.css"  rel="stylesheet" type="text/css">
</head>
<body>
<!-- [/begin] -->
<table width="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0 class="Container">
<tr>
<td valign="top">
<!-- [top] -->
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
   <TD class="Head" style="background-image: url('{xlite.layout.path}images/head.gif');" HEIGHT=74><IMG SRC="images/logo.gif" WIDTH=275 HEIGHT=60 BORDER="0" ALT=""></TD>
</TR>
</TABLE>
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
   <TD WIDTH=665 height=20 valign=top><IMG SRC="images/head_line.gif" WIDTH=665 HEIGHT=12 ALT=""></TD>
   <TD WIDTH="100%"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=12 ALT=""></TD>
</TR>
</TABLE>
<BR>
<!-- [/top] -->

<!-- [main_view] -->
<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
<tr>
    <td width="150" valign="top">
<!-- [left] -->
<widget template="modules/Affiliate/sidebar_box.tpl" dir="modules/Affiliate/management" name="managementWidget" head="Management" IF="{auth.logged}">
<widget template="modules/Affiliate/sidebar_box.tpl" dir="modules/Affiliate/profile" name="profileWidget" head="Your profile" IF="{auth.logged}">
<widget template="modules/Affiliate/sidebar_box.tpl" dir="modules/Affiliate/authentication" name="authenticationWidget" head="Authentication" IF="{auth.logged}">
<widget template="modules/Affiliate/sidebar_box.tpl" dir="modules/Affiliate/login" head="Authentication" IF="{!auth.logged}">
<widget template="modules/Affiliate/sidebar_box.tpl" head="Help" dir="modules/Affiliate/help">
<!-- [/left] -->
    </td>
    <td width="15">&nbsp;</td>
    <td valign="top">
<!-- [center] -->


<widget class="\XLite\View\Location" />
<widget target="partner" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/partner_menu.tpl" head="Partner menu">

<!-- [help] {{{ -->
<widget target="affiliate_help" mode="terms_conditions" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/terms_conditions.tpl" head="Terms & Conditions">
<widget target="affiliate_help" mode="privacy_statement" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/privacy_statement.tpl" head="Privacy statement">
<!-- [/help] }}} -->

<widget target="partner_profile" mode="modify" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/modify_profile.tpl" head="Modify profile" class="\XLite\View\RegisterForm" name="profileForm">
<widget target="partner_profile" mode="delete" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/delete_profile.tpl" head="Delete profile - Confirmation">
<widget target="partner_profile" mode="success" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/register_success.tpl" head="Registration success">
<widget target="partner_banners" mode="" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/banners.tpl" head="Banners">
<widget target="partner_banners" mode="home" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/banners_main.tpl" head="Main page banners">
<widget target="partner_banners" mode="affiliate" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/banners_affiliate.tpl" head="Affiliate register link">
<widget target="partner_banners" mode="categories" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/banners_category.tpl" head="Category banners">
<widget target="partner_banner_stats" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/banner_stats.tpl" head="Banner statistics">
<widget target="partner_sales" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/sales_stats.tpl" head="Referred sales">
<widget target="partner_summary" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/partner_summary.tpl" head="Summary statistics">
<widget target="partner_payments" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/partner_payments.tpl" head="Payments history">
<widget target="partner_affiliates" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/partner_affiliates.tpl" head="Affiliate tree">
<widget target="partner_products" template="modules/Affiliate/partner_products.tpl">
<widget target="partner_product" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/product_html.tpl" head="{product.name:h}">
<!-- [/center] -->
    </td>
    <td width="10">&nbsp;</td>
    <td width="0" valign="top">
<!-- [right] -->
<!-- [/right] -->
    </td>
</tr>
</table>

<!-- [/main_view] -->

</td>
</tr>

<TR><TD height="100%"><img src="skins/default/en/images/spacer.gif" width=1 height=1 alt=""></TD></TR>

<tr>
<td align="center">

<!-- [bottom] -->
<table WIDTH="100%" BORDER=0 CELLPADDING=3 CELLSPACING=0>
<tr>
<td bgcolor="#E0E0E0" HEIGHT=15 align=left>
&nbsp;<font color="#8A8A8A">Powered by LiteCommerce:</font> <a href="http://www.litecommerce.com"><font color="#8A8A8A"><u>ecommerce software</u></font></a>
</td>
<td bgcolor="#E0E0E0" HEIGHT=15 align=right>
<font color="#8A8A8A">Copyright &copy; 2004 {config.Company.company_name}</font>
&nbsp;</td>
</tr>
</table>
<!-- [/bottom] -->

</td>
</tr>
</table>
<!-- [end] -->
</body>
</html>
<!-- [/end] -->
