{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
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
    <h2>{t(#Environment info#)}</h2>
  </td>
</tr>	

<tr>
  <td class="setting-name">{t(#LiteCommerce version#)}:</td>
  <td>{lite_version:h}{if:answeredVersion}&nbsp;&nbsp;({t(#verified version#)}: {if:answeredVersionError}<span class="star">{t(#unknown#)}</span>{else:}{answeredVersion}{end:}){end:}</td>
</tr>

<tr IF="answeredVersionError">
  <td class="setting-name">{t(#Loopback test#)}:</td>
  <td><textarea name="answered_version" cols=80 rows=5 style="FONT-SIZE: 10px;" readonly>{answeredVersion}</textarea></td>
</tr>

<tr>
  <td class="setting-name">{t(#Installation directory#)}:</td>
  <td>{root_folder:h}</td>
</tr>

<tr>
  <td class="setting-name">PHP:</td>
  <td><span>{phpversion:h}&nbsp;</span><a href='{getShopURL(#admin.php#)}?target=settings&action=phpinfo' target='blank_' class="navigation-path"> <b>{t(#details#)} &gt;&gt;</b></a></td>
</tr>

<tr>
  <td class="setting-name">{t(#MySQL server#)}:</td>
  <td>
    <span>{mysql_server:h}&nbsp;</span>
    <span IF="innodb_support">({t(#InnoDB engine support enabled#)})</span>
    <span IF="!innodb_support" class="error-message">{t(#Warning! InnoDB engine is not supported. It is required for LiteCommerce operation#)}</span>
  </td>
</tr>

<tr>
  <td class="setting-name">{t(#Web server#)}:</td>
  <td>{web_server:h}</td>
</tr>

<tr>
  <td class="setting-name">{t(#Operating system#)}:</td>
  <td>{os_type:h}</td>
</tr>

<tr>
  <td class="setting-name">{t(#XML parser#)}:</td>
  <td>{xml_parser:h}</td>
</tr>

<tr>
  <td class="setting-name">GDLib:</td>
  <td>{if:gdlib}{gdlib}{else:}<span class="error-message">{t(#Not detected#)}</span><br /><b>{t(#Warning!#)}</b> {t(#PHP 'gd' extension is not installed.#)}{end:}</td>
</tr>

{if:!isWin()}
<tr>
	<td colspan="2">
    <h2>{t(#Directories and files permissions#)}</h2>
  </td>
</tr>

{foreach:check_dirs,k,v}
<tr class="{getRowClass(k,#dialog-box#,#highlight#)}">
	<td>{v.dir}</td>
	<td>
		{if:v.error=##}<span class="success-message">OK</span>{end:}
		{if:v.error=#cannot_create#}<span class="error-message">{t(#cannot create directory#)}</span>{end:}
		{if:v.error=#cannot_chmod#}<span class="error-message">{t(#cannot set X permissions#,_ARRAY_(#X#^getDirPermissionStr(v.dir)))}</span>{end:}
        {if:v.error=#wrong_owner#}<span class="error-message">{t(#incorrect owner for X directory#,_ARRAY_(#X#^v.dir))}</span>{end:}
        {if:v.error=#cannot_chmod_subdirs#}<span class="error-message">{t(#subdirectories problems#)}</span>&nbsp;&nbsp;<a href="javascript: setVisible('details_{k}')" class="navigation-path"><b>{t(#details#)}</b>&nbsp;&gt;&gt</a>{end:}
	</td>
</tr>
<tr class="{getRowClass(k,#dialog-box#,#highlight#)}" style="display : none" id="details_{k}" IF="v.error=#cannot_chmod_subdirs#">
    <td colspan="4">
        &nbsp;{t(#Cannot set X permissions for subdirectories:#,_ARRAY_(#X#^dirPermissionStr))}<br />
        {foreach:v.subdirs,sid,subdir}
            &nbsp;&nbsp;&nbsp;{subdir}<br />
        {end:}
    </td>
</tr>
{end:}

{foreach:check_files,k,v}
<tr class="{getRowClass(k,#dialog-box#,#highlight#)}">
  <td>{v.file}</td>
  <td>
 		{if:v.error=##}<span class="success-message">OK</span>{end:}
		{if:v.error=#does_not_exist#}<span class="error-message">{t(#file does not exist#)}</span>{end:}
		{if:v.error=#cannot_chmod#}<span class="error-message">{t(#cannot set X permissions#,_ARRAY_(#X#^getDirPermissionStr(v.file)))}</span>{end:}
    {if:v.error=#wrong_owner#}<span class="error-message">{t(#incorrect owner for X file#,_ARRAY_(#X#^v.file))}</span>{end:}
	</td>
</tr>
{end:}
{end:}
</table>
