{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
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

<table cellspacing="1" cellpadding="5" border="0" class="settings-table">

<tr>
	<td colspan="2">
    <h2>Active HTTPS clients</h2>
  </td>
</tr>	
<tr>
  <td class="setting-name">LibCurl:</td>
  <td>{if:check_https(#libcurl#)=#1#}<font class="ErrorMessage">Not detected</font>{else:}<font class="SuccessMessage">Detected</font> ({libcurl:h}) {end:}</td>
</tr>
<tr>
  <td class="setting-name">CURL:</td>
  <td>{if:check_https(#curl#)=#1#}<font class="ErrorMessage">Not detected</font>{else:}<font class="SuccessMessage">Detected</font> ({curl:h}) {end:}</td>
</tr>
<tr>
  <td class="setting-name">OpenSSL:</td>
  <td>{if:check_https(#openssl#)=#1#}<font class="ErrorMessage">Not detected</font>{else:}<font class="SuccessMessage">Detected</font> ({openssl:h}) {end:}</td>
</tr>

<tr>
	<td colspan="4"><b>Note:</b> if none of the above-mentioned components are present, you will not be able to use secure payment gateways (like Authorize.net, PayPal or NetBilling) which require direct SSL connection to the gateway's secure server.</td>
</tr>	

<tr>
	<td colspan="2">
    <h2>Environment info</h2>
  </td>
</tr>	

<tr>
  <td class="setting-name">LiteCommerce version:</td>
  <td>{lite_version:h}{if:answeredVersion}&nbsp;&nbsp;(verified version: {if:answeredVersionError}<font color=red><b>unknown</b></font>{else:}{answeredVersion}{end:}){end:}</td>
</tr>

<tr IF="answeredVersionError">
  <td class="setting-name">Loopback test:</td>
  <td><textarea name="answered_version" cols=80 rows=5 style="FONT-SIZE: 10px;" readonly>{answeredVersion}</textarea></td>
</tr>

<tr>
  <td class="setting-name">Installation directory:</td>
  <td>{root_folder:h}</td>
</tr>

<tr>
  <td class="setting-name">PHP:</td>
  <td>{phpversion:h}<a href='{getShopUrl(#admin.php#)}?target=settings&action=phpinfo' target='blank_' class="NavigationPath"> <b>details</b></a> &gt;&gt;</td>
</tr>

<tr>
  <td class="setting-name">MySQL server:</td>
  <td>{mysql_server:h}</td>
</tr>

<tr>
  <td class="setting-name">MySQL client:</td>
  <td>{mysql_client:h}</td>
</tr>

<tr>
  <td class="setting-name">Web server:</td>
  <td>{web_server:h}</td>
</tr>

<tr>
  <td class="setting-name">Operating system:</td>
  <td>{os_type:h}</td>
</tr>

<tr>
  <td class="setting-name">XML parser:</td>
  <td>{xml_parser:h}</td>
</tr>

<tr>
  <td class="setting-name">GDLib:</td>
  <td>{if:gdlib}{gdlib}{else:}<font class="ErrorMessage">Not detected</font><br><b>Warning:</b> PHP 'gd' extension is not installed. Captchas in customer zone will not work{end:}</td>
</tr>

{if:!isWin()}
<tr>
	<td colspan="2">
    <h2>Directories and files permissions</h2>
  </td>
</tr>

{foreach:check_dirs,k,v}
<tr class="{getRowClass(k,#DialogBox#,#TableRow#)}">
	<td>{v.dir}</td>
	<td>
		{if:v.error=##}<font class="SuccessMessage">OK</font>{end:}
		{if:v.error=#cannot_create#}<font class="ErrorMessage">cannot create directory</font>{end:}
		{if:v.error=#cannot_chmod#}<font class="ErrorMessage">cannot set {getDirPermissionStr(v.dir)} permissions</font>{end:}
        {if:v.error=#wrong_owner#}<font class="ErrorMessage">incorrect owner for {v.dir} directory</font>{end:}
        {if:v.error=#cannot_chmod_subdirs#}<font class="ErrorMessage">subdirectories problems</font>&nbsp;&nbsp;<a href="javascript: setVisible('details_{k}')" class="NavigationPath"><b>details</b>&nbsp;&gt;&gt</a>{end:}
	</td>
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
  <td>{v.file}</td>
  <td>
 		{if:v.error=##}<font class="SuccessMessage">OK</font>{end:}
		{if:v.error=#does_not_exist#}<font class="ErrorMessage">file does not exist</font>{end:}
		{if:v.error=#cannot_chmod#}<font class="ErrorMessage">cannot set {getDirPermissionStr(v.file)} permissions</font>{end:}
    {if:v.error=#wrong_owner#}<font class="ErrorMessage">incorrect owner for {v.file} file</font>{end:}
	</td>
</tr>
{end:}
{end:}
</table>
