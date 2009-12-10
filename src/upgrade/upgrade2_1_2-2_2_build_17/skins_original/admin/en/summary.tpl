<table cellSpacing=0 cellpadding=2 border=0 width="100%">

<tr>
	<td width="20%"class="TableHead" nowrap>&nbsp;<b>Active HTTPS clients</b>&nbsp;</td>
	<td colspan="3"></td>
</tr>	
<tr class="TableHead">
	<td colspan="4" height=2></td>
</tr>	
<tr>
    <td align=right>LibCurl:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{if:check_https(#libcurl#)=#1#}<font color=red><b>Not detected{else:}<font color=green><b>Detected</b></font> ({dialog.libcurl:h}) {end:}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>CURL:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{if:check_https(#curl#)=#1#}<font color=red><b>Not detected{else:}<font color=green><b>Detected</b></font> ({dialog.curl:h}) {end:}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>OpenSSL:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{if:check_https(#openssl#)=#1#}<font color=red><b>Not detected{else:}<font color=green><b>Detected</b></font> ({dialog.openssl:h}) {end:}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
	<td colspan="4"><b>Note:</b> if none of the above-mentioned components are present, you will not be able to use secure payment gateways (like Authorize.net, PayPal or NetBilling) which require direct SSL connection to the gateway's secure server.</td>
</tr>	

<tr>
	<td colspan="4">&nbsp;</td>
</tr>	
<tr>
	<td class="TableHead" nowrap>&nbsp;<b>Environment info</b>&nbsp;</td>
	<td colspan="3"></td>
</tr>	
<tr class="TableHead">
	<td colspan="4" height=2></td>
</tr>	
<tr>
    <td align=right>LiteCommerce version:</td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td>{dialog.lite_version:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>Installation directory:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{dialog.root_folder:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>PHP:</td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td>{dialog.phpversion:h}<a href={ShopURL(#admin.php?target=settings&action=phpinfo#)} target='blank_' class="NavigationPath"> <b>details</b></a> >></td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>MySQL server:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{dialog.mysql_server:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>MySQL client:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{dialog.mysql_client:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>Web server:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{dialog.web_server:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>Operating system:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{dialog.os_type:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>XML parser:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{dialog.xml_parser:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>

<tr>
	<td colspan="4">&nbsp;</td>
</tr>
<tr>
	<td class="TableHead" nowrap>&nbsp;<b>Directories permissions</b>&nbsp;</td>
	<td colspan="3"></td>
</tr>
	<tr class="TableHead">
	<td colspan="4" height=2></td>
</tr>
<tr FOREACH="dialog.check_dirs,k,v" class="{getRowClass(k,#DialogBox#,#TableRow#)}">
	<td align=left>{v.dir}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
	<td align=left>
		{if:v.error=##}<font color=green><b>OK</b></font>{end:}
		{if:v.error=#cannot_create#}<font color=red><b>cannot create directory</b></font>{end:}
		{if:v.error=#cannot_chmod#}<font color=red><b>cannot set 0777 permissions</b></font>{end:}
	</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
</table>
