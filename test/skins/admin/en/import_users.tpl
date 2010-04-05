{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Users import page template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<p>Use this section to import user data from a CSV file. <hr>

<p IF="import_error">
<font class="ErrorMessage">
&gt;&gt; Import error occured &lt;&lt;<br>
You have specified incorrect file format or the file doesn't match. 
</font>
</p>

<p>
<form action="admin.php" method=post enctype="multipart/form-data" name=data_form>
<input type="hidden" name="target" value="import_users">
<input type="hidden" name="action" value="import">

<table border=0>
<tr>
    <td colspan=2><font class=AdminHead>Field order:</font></td>
</tr>
<tr FOREACH="import_fields,id,fields">
    <td width=1>{id}:</td>
    <td width=99%>
        <select name="user_layout[{id}]">
            <option FOREACH="fields,field,value" value="{field}" selected="{value}">{field}</option>
        </select>
    </td>
</tr>
<tr>
    <td colspan="2">
    <input type=button value="Save field order" onClick="javascript: document.data_form.action.value='layout'; document.data_form.submit();">
    </td>
</tr>
</table>

<table border="0">
<tr>
    <td colspan=2>
    <br>
    Field delimiter:<br><widget template="common/delimiter.tpl">
    </td>
</tr>
<tr>
    <td colspan=2>
    Text qualifier:<br><widget template="common/qualifier.tpl">
    </td>
</tr>
<tr>
    <td colspan=2>
    File (CSV) local:<br>
    <input type=text size=32 name=localfile>
    </td>
</tr>
<tr>
    <td colspan=2>
    File (CSV) for upload:<br><input type=file size=32 name=userfile><widget IF="invalid_file" template="common/uploaded_file_validator.tpl" />
    <br><br>
    <input type="checkbox" value="yes" name="md5_import" checked="{md5_import}" />Import password as md5 hash<br /><br />
    <input type=submit value="Import users" class="DialogMainButton">
    </td>
</tr>
</table>
</form>
