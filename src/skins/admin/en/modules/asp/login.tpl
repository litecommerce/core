<html>
<head>
    <title>LiteCommerce ASPE Control Center Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<LINK href="skins/admin/en/style.css"  rel=stylesheet type=text/css>
	<LINK href="skins/admin/en/modules/asp/style.css"  rel=stylesheet type=text/css>
</head>
<BODY CLASS="ASPE_CC" LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 BOTTOMMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 background="skins/admin/en/images/zebra.gif">

<!-- [top] -->
<TABLE WIDTH="620" BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
<TR>
   <TD WIDTH=494 HEIGHT=60 valign="bottom"><IMG SRC="images/logo_aspe_cc.gif" WIDTH=275 HEIGHT=52 BORDER="0" alt="LiteCommerce ASPE Control Center"></TD>
</TR>
<tr><td height=10>&nbsp;</td></tr>
</TABLE>
<!-- [/top] -->

<TABLE WIDTH="500" ALIGN="center" BORDER=0 BORDERCOLOR="#FFF2C9" CELLPADDING=2 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
   <TD class="CenterBorder">
   <table border=0 cellspacing=0 cellpadding=15 width="100%" class="Center">
   <tr><td>

<H2 align="center">Welcome to LiteCommerce ASPE</H2>

<p align=center style="font-size: 11px">Please identify yourself with a username and a password</p>
<br>
<form action="cpanel.php?target=login&action=login" method="POST">
	<table align="center" border="0" cellspacing=4>
	<tr>
	<td> Login: </td>
	<td> <input name="login" size=32> </td>
	</tr>
	<tr>
	<td> Password: </td>
	<td> <input type=password name="password" size=32> </td>
	</tr>
	<tr>
	<td colspan=2 align="right"> <input type="submit" value="Log in" class="DialogMainButton" onClick="this.blur();"> </td>	
	</tr>
	</table>
</form>

	</td></tr>
	</table>
    </TD>
	</TR>
</TABLE>

</body>
</html>
