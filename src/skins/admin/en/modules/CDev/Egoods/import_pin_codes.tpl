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
This page allows you to import PIN codes from CSV file.<hr>

<p IF="!valid">
    <font class="ErrorMessage">&gt;&gt; Error occured &lt;&lt;<br></font>
</p>

<p>
<form action="admin.php" method=POST name=data_form enctype="multipart/form-data" >
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="import_pin_codes">

<table border=0>
<tr>
    <td colspan=2><font class=AdminHead>Field order:</font></td>
</tr>
<tr FOREACH="xlite.factory.\XLite\Module\CDev\Egoods\Model\PinCode.getImportFields(#pin_codes_layout#),id,fields">
    <td width=1>{id}:</td>
    <td width=99%>
        <select name="pin_codes_layout[{id}]">
            <option FOREACH="fields,field,value" value="{field}" selected="{value}">{field}</option>
        </select>
    </td>
</tr>
</table>
<br>
Field delimiter:<br><widget template="common/delimiter.tpl"><br>
<br>
Text qualifier:<br><widget template="common/qualifier.tpl"><br>
<br>
File (CSV) local:<br><input type=text size=32 name=localfile><widget IF="invalid_localfile" template="common/uploaded_file_validator.tpl" state="{invalid_localfile_state}" /><br>
<br>
File (CSV) for upload:<br><input type=file size=32 name=userfile><widget IF="invalid_userfile" template="common/uploaded_file_validator.tpl" state="{invalid_userfile_state}" /><br>
<br>
<input type=checkbox name="update_existing" value="1" checked onClick="this.blur()"> Update existing PIN codes
<br>
<br>
<input type=submit value=" Import ">

</form>
