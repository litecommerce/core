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
<script type="text/javascript">
<!--
function setVisible(element_id)
{
    var element = document.getElementById(element_id);
    if(!element) return;

    if(element.style.display == ''){
        element.style.display = 'none';
    } else {
        element.style.display = '';
    }
}
//-->
</script>
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
    <td>{if:check_https(#libcurl#)=#1#}<font class="ErrorMessage">Not detected</font>{else:}<font class="SuccessMessage">Detected</font> ({libcurl:h}) {end:}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>CURL:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{if:check_https(#curl#)=#1#}<font class="ErrorMessage">Not detected</font>{else:}<font class="SuccessMessage">Detected</font> ({curl:h}) {end:}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>OpenSSL:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{if:check_https(#openssl#)=#1#}<font class="ErrorMessage">Not detected</font>{else:}<font class="SuccessMessage">Detected</font> ({openssl:h}) {end:}</td>
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
    <td>{lite_version:h}{if:answeredVersion}&nbsp;&nbsp;(verified version: {if:answeredVersionError}<font color=red><b>unknown</b></font>{else:}{answeredVersion}{end:}){end:}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr IF="answeredVersionError">
    <td align=right>Loopback test:</td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td><textarea name="answered_version" cols=80 rows=5 style="FONT-SIZE: 10px;" readonly>{answeredVersion}</textarea></td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>Installation directory:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{root_folder:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>PHP:</td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td>{phpversion:h}<a href='{getShopUrl(#admin.php#)}?target=settings&action=phpinfo' target='blank_' class="NavigationPath"> <b>details</b></a> &gt;&gt;</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>MySQL server:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{mysql_server:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>MySQL client:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{mysql_client:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>Web server:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{web_server:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>Operating system:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{os_type:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>XML parser:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{xml_parser:h}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td align=right>GDLib:</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>{if:gdlib}{gdlib}{else:}<font class="ErrorMessage">Not detected</font><br><b>Warning:</b> PHP 'gd' extension is not installed. Captchas in customer zone will not work{end:}</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>

{if:!isWin()}
<tr>
	<td colspan="4">&nbsp;</td>
</tr>
<tr>
	<td class="TableHead" nowrap>&nbsp;<b>Directories and files permissions</b>&nbsp;</td>
	<td colspan="3"></td>
</tr>
	<tr class="TableHead">
	<td colspan="4" height=2></td>
</tr>
{foreach:check_dirs,k,v}
<tr class="{getRowClass(k,#DialogBox#,#TableRow#)}">
	<td align=left>
        {v.dir}
    </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
	<td align=left>
		{if:v.error=##}<font class="SuccessMessage">OK</font>{end:}
		{if:v.error=#cannot_create#}<font class="ErrorMessage">cannot create directory</font>{end:}
		{if:v.error=#cannot_chmod#}<font class="ErrorMessage">cannot set {getDirPermissionStr(v.dir)} permissions</font>{end:}
        {if:v.error=#wrong_owner#}<font class="ErrorMessage">incorrect owner for {v.dir} directory</font>{end:}
        {if:v.error=#cannot_chmod_subdirs#}<font class="ErrorMessage">subdirectories problems</font>&nbsp;&nbsp;<a href="javascript: setVisible('details_{k}')" class="NavigationPath"><b>details</b>&nbsp;&gt;&gt</a>{end:}
	</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr class="{getRowClass(k,#DialogBox#,#TableRow#)}" style="display : none" id="details_{k}" IF="v.error=#cannot_chmod_subdirs#">
    <td colspan="4">
        &nbsp;Cannot set {dirPermissionStr} permissions for subdirectories:<br />
        {foreach:v.subdirs,sid,subdir}
            &nbsp;&nbsp;&nbsp;<i>{subdir}</i><br />    
        {end:}
    </td>
</tr>
{end:}
{foreach:check_files,k,v}
<tr class="{getRowClass(k,#DialogBox#,#TableRow#)}">
    <td align=left>
        {v.file}
    </td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td align=left>
 		{if:v.error=##}<font class="SuccessMessage">OK</font>{end:}
		{if:v.error=#does_not_exist#}<font class="ErrorMessage">file does not exist</font>{end:}
		{if:v.error=#cannot_chmod#}<font class="ErrorMessage">cannot set {getDirPermissionStr(v.file)} permissions</font>{end:}
        {if:v.error=#wrong_owner#}<font class="ErrorMessage">incorrect owner for {v.file} file</font>{end:}
	</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
</tr>
{end:}
{end:}
{if:config.Security.htaccess_protection=#Y#}
<tr>
    <td colspan="4">&nbsp;</td>
</tr>
<tr>
    <td class="TableHead" nowrap>&nbsp;<b>Security files verification</b>&nbsp;</td>
    <td colspan="3"></td>
</tr>
<tr class="TableHead">
    <td colspan="4" height=2></td>
</tr>
<form action="admin.php" name="update_htaccess_form">
<input FOREACH="allparams,key,val" type="hidden" name="{key}" value="{val:r}" />
<input type="hidden" name="action" value="update_htaccess" />
<tr FOREACH="checkFiles,k,v" class="{getRowClass(k,#DialogBox#,#TableRow#)}">
    <td align=left><input type="checkbox" name="ind[{v.id}]"{if:v.status=#ok#}disabled="1" {end:} />&nbsp;{v.filename}</td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td align=left>
        {if:v.status=#ok#}<font class="SuccessMessage"><b>OK</b></font>{end:}
        {if:v.status=#not_exists#}<font class="ErrorMessage"><b>MISSING</b></font>{end:}
        {if:v.status=#wrong#}<font class="ErrorMessage"><b>FAILED</b></font>{end:}
    </td>
    <td>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
    <td colspan="4" height=2>&nbsp;</td>
</tr>
<tr>
    <td colspan="4" height=2>
        <b>Note:</b> If you have modified any of .htaccess files and want to save the modified version to the database, select a check box next to the necessary file and click 'Update selected'.<br /><br/>
        <input type="button" value=" Update selected " onClick="javascript: if(confirm('Are you sure you want to save the modified file(s) to the database? The operation cannot be reversed.')) htaccess_action(this.form, 'update_htaccess'); " />&nbsp;&nbsp;
        <input type="button" value=" Restore selected " onClick="javascript: if(confirm('Are you sure you want to restore the file(s) from the database? The operation cannot be reversed.')) htaccess_action(this.form, 'restore_htaccess'); " />
    </td>
</tr>
</form>
<script type="text/javascript">
<!--
    function htaccess_action(form, action)
    {
        form.action.value = action;
        form.submit();
    }
// -->
</script>
{end:}
</table>
