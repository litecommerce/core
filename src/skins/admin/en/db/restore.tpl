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
<script language="Javascript">
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
Use this section to restore the database of your online store. Please note that database restore procedure can take up to several minutes.
<span id="notes_url" style="display:"><a href="javascript:ShowNotes();" class="NavigationPath" onClick="this.blur()"><b>How to restore your LiteCommerce database &gt;&gt;&gt;</b></a></span>
<span id="notes_body" style="display: none">
<p class="adminParagraph">
<table border=0 cellpadding=5 cellspacing=0>
    <tr>
        <td>&nbsp;&nbsp;</td>
        <td class="TableHead">&nbsp;&nbsp;</td>
        <td>&nbsp;</td>
        <td>
You can upload the database data directly from your local computer by clicking on the 'Browse' button, choosing an SQL dump file and clicking on the 'Upload and restore' button.  While this method is more convenient, it has a file size limitation of <b>{getUploadMaxFilesize():h}</b>.
<br><br>
Alternatively, upload the file named 'sqldump.sql.php' to the 'var/backup/' sub-directory of your LiteCommerce installation on the web server and click on the
'Restore from server' button. After the restore you might want to delete the file from the server by clicking on the 'Delete SQL file' button above.
<br><br>
To restore the images which are stored in the file system, you have to copy them from the archive to LiteCommerce catalog, taking into consideration the catalog structure.
        </td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
</table>
</p>
</span>

<hr>
<span class="ErrorMessage" IF="error">{error:h}<br></span>
<p class="adminParagraph">
<b class="Star">Warning:</b> Restore procedure is irreversible and erases all data tables from your store database. It is highly recommended that you backup your present database data before restoring one of the previous states from a backup.
</p>
<p class="adminParagraph"><b class="Star">Warning:</b> It is strongly recommended that you close the shop for maintenance on the <a href="admin.php?target=settings"><u>General settings</u></a> page before performing restore procedure!</p>

<form action="admin.php" method=post enctype="multipart/form-data">
<input type="hidden" name="target" value="db">
<input type="hidden" name="action" value="restore">
<input type="hidden" name="page" value="{page}">
<table border=0 cellpadding=0 cellspacing=0>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td><input type=file name=userfile onChange="javascript: visibleBox('max_upload_note', true);"><widget IF="invalid_file" template="common/uploaded_file_validator.tpl" /></td>
	<td>&nbsp;&nbsp;&nbsp;<input type=submit value="Upload and restore" class="DialogMainButton"></td>
    <td>{if:fileExists}&nbsp;&nbsp;&nbsp;<input type=submit name="local_file" value="Restore from server">{end:}</td>
</tr>
</table>
<span id="max_upload_note" style="display: none">
<br>
<b class="Star">Warning:</b> Maximum size of the file to upload is <b>{getUploadMaxFilesize():h}</b>.
</form>
</span>
