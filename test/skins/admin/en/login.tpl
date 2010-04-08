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

<table border=0 width="100%" height="100%">
<tr>
<td valign=top>

<!-- [top] -->
<table width="100%" border=0 cellpadding=0 cellspacing=0>
<tr>
   <td class="Head" background="images/head_demo_01.gif" width=494 height=73><img src="images/logo_demo.gif" width=275 height=60 border="0"><br><img src="images/spacer.gif" width=494 height=1 border="0"></td>
   <td class="Head" background="images/head_demo_02.gif" width="100%">
   <img src="images/spacer.gif" width=1 height=1 border="0"></td>
</tr>
</table>
<table width="100%" border=0 cellpadding=0 cellspacing=0>
<tr>
   <td width="100%" background="skins/admin/en/images/head_line.gif" height=12 align=right>Version: {config.Version.version}</td>
</tr>
</table>
<br>
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
            	<table align="center" border="0" cellspacing=4>
                <form id="login_form" action="{loginURL}" method="POST" name="login_form">
                <input type="hidden" name="target" value="login">
                <input type="hidden" name="action" value="login">
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
            	</form>
            	</table>
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
