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
   <TD class="Head" style="background-image: url('{xlite.layout.path}images/head.gif');" HEIGHT=74><IMG SRC="images/logo.gif" WIDTH=275 HEIGHT=60 BORDER="0" alt=""></TD>
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
<widget template="modules/Affiliate/sidebar_box.tpl" dir="modules/Affiliate/login" head="Authentication">
<widget template="modules/Affiliate/sidebar_box.tpl" head="Help" dir="modules/Affiliate/help">
<!-- [/left] -->
    </td>
    <td width="15">&nbsp;</td>
    <td valign="top">
<!-- [center] -->
<widget target="partner_login" template="modules/Affiliate/welcome.tpl" visible="{action=##&mode=##}">
<widget target="partner_login" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/authentication_error.tpl" head="Authentication" visible="{action=#login#}">
<widget IF="config.Affiliate.registration_enabled" target="partner_profile" mode="register" class="\XLite\View\RegisterForm" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/register_form.tpl" head="A new partner" name="registerForm"/>
<widget IF="!config.Affiliate.registration_enabled" target="partner_profile" mode="register" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/register_closed.tpl" head="Registration closed"/>
<widget target="partner_profile" mode="sent" template="modules/Affiliate/dialog.tpl" body="modules/Affiliate/request_sent.tpl" head="A new partner">
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
