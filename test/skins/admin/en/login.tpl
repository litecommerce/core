{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Login page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<!-- [begin] -->
<html>
<head>
    <title>LiteCommerce online store builder</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta http-equiv="Content-Type" content="text/html; charset={charset}">
    <LINK href="skins/admin/en/style.css"  rel=stylesheet type=text/css>
</head>
<body  onLoad="if (document.getElementById('login_form') != null) document.login_form.login.focus();" LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 BOTTOMMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 background="skins/admin/en/images/zebra.gif">
<!-- [/begin] -->

<table border=0 width="100%" height="100%" cellpadding=0 cellspacing=0>
<tr>
<td valign=top>

<widget class="XLite_View_TopMessage" />

<!-- [top] -->
<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; border-bottom: 1px solid #e9ecf3;">
<tr>
   <td style="padding: 10px;"><img src="images/logo.png" alt="" /></td>
    <td style="white-space: nowrap;">
      <div style="font-size: 24px;"><span style="color: #2d69ab;">Lite</span><span style="color: #676767;">Commerce</span></div>
      <div>Version: {config.Version.version:h}</div>
    </td>
    <td align="right" valign="top" nowrap="nowrap" width="100%">
      <img src="images/spacer.gif" width="100%" height="1" alt="" />
    </td>
</tr>
</table>
<br />
<!-- [/top] -->

<!-- [main_view] -->
<TABLE border="0" align="center" cellpadding="0" cellspacing="0">
<TR>
    <TD valign="top">
    <noscript>
        <table border=0 width=500 cellpadding=2 cellspacing=0 align=center>
        <tr>
            <td align=center class=ErrorMessage nowrap>This site requires JavaScript to function properly.<br>Please enable JavaScript in your web browser.</td>
        </tr>
        </table>
    </noscript>
    <script language="JavaScript">
    function isSetCookie()
    {
        return navigator.cookieEnabled;
    }
    if (!isSetCookie()) {
        document.write("<table border=0 width=500 cellpadding=2 cellspacing=0 align=center>");
        document.write("<tr>");
        document.write("<td align=center class=ErrorMessage nowrap>");
        document.write("This site requires cookies to function properly.<br>Please enable cookies in your web browser.");
        document.write("</td>");
        document.write("</tr>");
        document.write("</table>");
    }
    </script>
    </TD>
</TR>
</TABLE>
<table width="500" align="center" border=0 cellpadding=2 cellspacing=0>
<tr>
   <td><br><br><br><br></td>
</tr>
<tr>
  <td class="CenterBorder">
    <table border=0 cellspacing=0 cellpadding=15 width="100%" class="Center">
    <tr>
      <td>
            <widget template="welcome.tpl" mode="">
            <widget template="access_denied.tpl" mode="access_denied">

            <p align=center style="font-size: 11px" class="tabDefault"><b>Please identify yourself with a username and a password to access the Administrator Zone</b></p>
              <form id="login_form" action="{buildUrl(#login#)}" method="POST" name="login_form">
              <input type="hidden" name="target" value="login">
              <input type="hidden" name="action" value="login">
              <table align="center" border="0" cellspacing=4>
              <tr>
                <td rowspan=2 valign=middle><img src="images/keys.gif" width=28 height=32 border=0 alt=""></td>
                <td rowspan=2 valign=top>&nbsp;&nbsp;&nbsp;</td>
                <td> Login: </td>
                <td> <input type="text" name="login" value="{login:r}" size="32" maxlength="128"> </td>
              </tr>
              <tr>
                <td> Password: </td>
                <td> <input type="password" name="password" value="{password:r}" size="32" maxlength="128"> </td>
              </tr>
              <tr>
                <td colspan=4 align="right"> <input type="Submit" value="Log in"> </td>  
              </tr>
              </table>
              </form>
      </td>
    </tr>
      <tr>
        <td align=right><a href="cart.php?target=recover_password"><u>Forgot password?</u></a></td>
      </tr>
    </table>
    </td>
  </tr>
</table>

<!-- [/main_view] -->

</td>
</tr>

<!-- align code -->
<script language="JavaScript">
if (navigator.appName.indexOf('Microsoft') >= 0) {
    document.write('<TR><TD height="100%"><img src="images/spacer.gif" width=1 height=1></TD></TR>');
} else {
    document.write('<TR><TD><img src="images/spacer.gif" width=1 height=1></TD></TR>');
}    
</script>

<tr>
<td align="center">
<!-- [bottom] -->
<widget class="XLite_View_PoweredBy" />
<!-- [/bottom] -->

</td>
</tr>
</table>

<!-- [end] -->
</body>
</html>
<!-- [/end] -->
