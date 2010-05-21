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
<script>
//<!--
function newTemplate()
{
    new_name = document.ecard_form.new_template.value;
    new_name = prompt("Create template named (without .tpl): ", new_name);
    document.ecard_form.new_template.value = new_name;
    if (new_name != null && new_name != "") {
        window.open("admin.php?target=template_editor&editor=advanced&zone=default&action=new_file&node=skins/mail/en/modules/GiftCertificates/ecards&file=" + new_name + ".tpl");
    }
}
function editTemplate()
{
    var index = document.ecard_form.template.selectedIndex;
    if (index>=0) {
    var new_name = document.ecard_form.template.options[index].text;
    if (new_name != null && new_name != "") {
        window.open("admin.php?target=template_editor&editor=advanced&zone=default&mode=edit&file=skins/mail/en/modules/GiftCertificates/ecards/" + new_name + ".tpl");
    }
    }
}

//-->
</script>
<table border="0">
<form action="admin.php" method="POST" name="ecard_form" enctype="multipart/form-data">
<input type="hidden" name="target" value="gift_certificate_ecard">
<input type="hidden" name="action" value="update">
<input type="hidden" name="ecard_id" value="{ecard.ecard_id}">
<tr valign="top">
    <td align="right">Thumbnail</td>
    <td width="10"><font class="Star">*</font></td>
    <td>
        {if:ecard.isPersistent}<img src="{ecard.thumbnail.url}?{rand()}">{end:}<br>
        <widget class="XLite_View_ImageUpload" field="thumbnail" actionName="images" formName="modify_form" object="{ecard}">
    </td>
</tr>
<tr valign="top">
    <td align="right">Image</td>
    <td width="10"><font class="Star">*</font></td>
    <td>
        {if:ecard.isPersistent}<img src="{ecard.image.url}?{rand()}">{end:}<br>
        <widget class="XLite_View_ImageUpload" field="image" actionName="images" formName="modify_form" object="{ecard}">
    </td>
</tr>
<tr valign="top">
    <td align="right">Existing template</td>
    <td width="10"></td>
    <td>
        <select name="template">
        <option FOREACH="ecard.allTemplates,_template" selected="{ecard.template=_template}">{_template}</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:editTemplate()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Edit template</a>
        <br>&nbsp;
    </td>
</tr>
<tr valign="top">
    <td align="right">New template</td>
    <td width="10"></td>
    <td>
        <input type="text" name="new_template" value="">
        &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:newTemplate()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Create template</a>
    </td>
</tr>
<tr valign="top">
    <td align="right">Pos.</td>
    <td width="10"><font class="Star">*</font></td>
    <td>
        <input type="text" name="order_by" value="{ecard.order_by}" size="4">
        <br>&nbsp;
    </td>
</tr>
<tr valign="top">
    <td align="right">Show in customer zone</td>
    <td width="10"><font class="Star">*</font></td>
    <td>
        <input type="checkbox" name="enabled" value="1" checked="{ecard.enabled}">
    </td>
</tr>
<tr><td colspan="3" align="center">&nbsp;</td></tr>
<tr><td colspan="3" align="center">
<a href="javascript:document.ecard_form.submit()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Submit e-Card</a>
</td></tr>
</form>
</table>
