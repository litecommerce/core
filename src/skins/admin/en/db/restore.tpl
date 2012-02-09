{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Database restore tab template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}


<script type="text/javascript">
function visibleBox(id, status)
{
        var Element = document.getElementById(id);
        if (Element) {
            Element.style.display = ((status) ? "" : "none");
        }
}
function ShowNotes()
{
        visibleBox("notes_url", false);
        visibleBox("notes_body", true);
}
</script>

<style>
	.adminParagraph {
		text-align 	: justify;	
	}
</style>

{t(#Use this section to restore the database of your online store. Please note that database restore procedure can take up to several minutes.#)}

<span id="notes_url" style="display:">
  <a href="javascript:ShowNotes();" class="navigation-path" onclick="this.blur()">
    <b>{t(#How to restore your LiteCommerce database#)} &gt;&gt;&gt;</b>
  </a>
</span>

<div id="notes_body" style="display: none">

<p class="adminParagraph">

<table cellpadding="5" cellspacing="0">
    <tr>
        <td>&nbsp;&nbsp;</td>
        <td>&nbsp;&nbsp;</td>
        <td>&nbsp;</td>
        <td>
        {t(#You can upload the database data directly from your local computer#,_ARRAY_(#N#^getUploadMaxFilesize())):h}
        <br /><br />
        {t(#Alternatively, upload file sqldump.sql.php to the var/backup/ sub-directory click on the "Restore from server" button#)}
        <br /><br />
        {t(#To restore the images which are stored in the file system, you have to copy them from the archive to LiteCommerce catalog#)}
        </td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
</table>
</p>

</div>

<hr />

<span class="error-message" IF="error">{error:h}<br /></span>

<p class="adminParagraph">

<b class="star">{t(#Warning#)}:</b> 
{t(#The restoration procedure is irreversible and erases all data tables from your store database. It is highly recommended that you back your present database data up before restoring one of the previous states from a back-up.#)}
</p>

<form action="admin.php" method="post" id="dbform" enctype="multipart/form-data">

<input type="hidden" name="target" value="db_restore" />
<input type="hidden" name="action" value="" />
<input type="hidden" name="page" value="{page}" />

<table cellpadding="0" cellspacing="0">

<tr>
	<td colspan="3">&nbsp;</td>
</tr>

<tr>

	<td>
    <input class="input-file" type="file" name="userfile" onchange="javascript: visibleBox('max_upload_note', true);"><widget IF="invalid_file" template="common/uploaded_file_validator.tpl" />
  </td>

	<td>&nbsp;&nbsp;&nbsp;<widget class="\XLite\View\Button\Regular" label="{t(#Upload and restore#)}" style="main-button" jsCode="submitFormDefault(this.form, 'restore_from_uploaded_file');" /></td>

  <td>{if:isFileExists()}&nbsp;&nbsp;&nbsp;<widget class="\XLite\View\Button\Regular" label="{t(#Restore from server#)}" jsCode="submitFormDefault(this.form, 'restore_from_local_file');" />{end:}</td>

</tr>

</table>

<span id="max_upload_note" style="display: none">

<br />

<b class="star">{t(#Warning#)}:</b> {t(#Maximum size of the file to upload is N#,_ARRAY_(#N#^getUploadMaxFilesize())):h}

</span>

</form>
