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

<table cellspacing="1" cellpadding="5" class="settings-table">

<tr>
	<td colspan="2">
    <h2>Active HTTPS clients</h2>
  </td>
</tr>	
<tr>
  <td class="setting-name">LibCurl:</td>
  <td>{if:check_https(#libcurl#)=#1#}<span class="error-message">Not detected</span>{else:}<span class="success-message">Detected ({libcurl:h})</span> {end:}</td>
</tr>
<tr>
  <td class="setting-name">CURL:</td>
  <td>{if:check_https(#curl#)=#1#}<span class="error-message">Not detected</span>{else:}<span class="success-message">Detected ({curl:h})</span> {end:}</td>
</tr>
<tr>
  <td class="setting-name">OpenSSL:</td>
  <td>{if:check_https(#openssl#)=#1#}<span class="error-message">Not detected</span>{else:}<span class="success-message">Detected ({openssl:h})</span> {end:}</td>
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
  <td>{lite_version:h}{if:answeredVersion}&nbsp;&nbsp;(verified version: {if:answeredVersionError}<span class="star">unknown</span>{else:}{answeredVersion}{end:}){end:}</td>
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
  <td>{phpversion:h}<a href='{getShopUrl(#admin.php#)}?target=settings&action=phpinfo' target='blank_' class="navigation-path"> <b>details</b></a> &gt;&gt;</td>
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
  <td>{if:gdlib}{gdlib}{else:}<span class="error-message">Not detected</span><br /><b>Warning:</b> PHP 'gd' extension is not installed. Captchas in customer zone will not work{end:}</td>
</tr>

{if:!isWin()}
<tr>
	<td colspan="2">
    <h2>Directories and files permissions</h2>
  </td>
</tr>

{foreach:check_dirs,k,v}
<tr class="{getRowClass(k,#dialog-box#,#highlight#)}">
	<td>{v.dir}</td>
	<td>
		{if:v.error=##}<span class="success-message">OK</span>{end:}
		{if:v.error=#cannot_create#}<span class="error-message">cannot create directory</span>{end:}
		{if:v.error=#cannot_chmod#}<span class="error-message">cannot set {getDirPermissionStr(v.dir)} permissions</span>{end:}
        {if:v.error=#wrong_owner#}<span class="error-message">incorrect owner for {v.dir} directory</span>{end:}
        {if:v.error=#cannot_chmod_subdirs#}<span class="error-message">subdirectories problems</span>&nbsp;&nbsp;<a href="javascript: setVisible('details_{k}')" class="navigation-path"><b>details</b>&nbsp;&gt;&gt</a>{end:}
	</td>
</tr>
<tr class="{getRowClass(k,#dialog-box#,#highlight#)}" style="display : none" id="details_{k}" IF="v.error=#cannot_chmod_subdirs#">
    <td colspan="4">
        &nbsp;Cannot set {dirPermissionStr} permissions for subdirectories:<br />
        {foreach:v.subdirs,sid,subdir}
            &nbsp;&nbsp;&nbsp;<i>{subdir}</i><br />    
        {end:}
    </td>
</tr>
{end:}

{foreach:check_files,k,v}
<tr class="{getRowClass(k,#dialog-box#,#highlight#)}">
  <td>{v.file}</td>
  <td>
 		{if:v.error=##}<span class="success-message">OK</span>{end:}
		{if:v.error=#does_not_exist#}<span class="error-message">file does not exist</span>{end:}
		{if:v.error=#cannot_chmod#}<span class="error-message">cannot set {getDirPermissionStr(v.file)} permissions</span>{end:}
    {if:v.error=#wrong_owner#}<span class="error-message">incorrect owner for {v.file} file</span>{end:}
	</td>
</tr>
{end:}
{end:}
</table>
