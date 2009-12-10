<html>
<head>
    <title>LiteCommerce ASPE Control Center</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<LINK href="skins/admin/en/style.css"  rel=stylesheet type=text/css>
	<LINK href="skins/admin/en/modules/asp/style.css"  rel=stylesheet type=text/css>
</head>
<BODY CLASS="ASPE_CC" LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 BOTTOMMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 background="skins/admin/en/images/zebra.gif">

<!-- [top] -->
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
   <TD WIDTH=494 HEIGHT=60 VALIGN="bottom"><IMG SRC="images/logo_aspe_cc.gif" WIDTH=275 HEIGHT=52 BORDER="0" alt="LiteCommerce ASPE Control Center"></TD>
   <td class="FormButton" align="right" valign="top">
   	  <br clear="all">
      <a href="cpanel.php?target=login&action=logoff"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Log Off</a>
   </td>
   <td width="25">&nbsp;</td>
</TR>
</TABLE>
<!-- [/top] -->

<!-- center><h3>{target}</h3></center -->
<table border=0 width="100%">
<tr>
<td width="15">&nbsp;</td>
<td>
<!-- used templates -->
<widget target="shops,modules,license,options,profiles,defaultShop" class="CTabber" body="{pageTemplate}" switch="target">
<!-- end -->
</td>
<td width="15">&nbsp;</td>
</tr>
</table>

</body>
</html>
