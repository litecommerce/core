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
		text-align  : justify;
    }
</style>
Use this section to backup the database of your online store. Please note
that database backup procedure can take up to several minutes.
<span id="notes_url" style="display:"><a href="javascript:ShowNotes();" class="NavigationPath" onClick="this.blur()"><b>How to backup your store database &gt;&gt;&gt;</b></a></span>
<span id="notes_body" style="display: none">
<p class="adminParagraph">
<table border=0 cellpadding=5 cellspacing=0>
    <tr>
        <td>&nbsp;&nbsp;</td>
        <td class="TableHead">&nbsp;&nbsp;</td>
        <td>&nbsp;</td>
        <td>
You can choose to download your database data (SQL dump) directly to your local
computer by clicking on the 'Download SQL file' button, or save database data to a file on the web server ('var/backup/sqldump.sql.php') by clicking on the
'Create SQL file' button. If you choose the second option, you can download the
file from the server later on and delete it from the server by clicking on the
'Delete SQL file' button.
        </td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
</table>
</p>
</span>

<hr>
<p class="adminParagraph"><b class="Star">Warning:</b> It is strongly recommended that you close the shop for maintenance on the <a href="admin.php?target=settings"><u>General settings</u></a> page before performing backup procedure!</p>

<form action="admin.php" method="post" name="backup_form">
<input type="hidden" name="target" value="db">
<input type="hidden" name="action" value="backup">
<input type="hidden" name="write_to_file" value="0">
<table border=0 cellpadding=0 cellspacing=0>
    <tr>
        <td colspan='3'>&nbsp;</td>
    </tr>
	<tr>
        <td><input type=submit value="Download SQL file" class="DialogMainButton"></td>
		<td>
		{if:fileWritable}&nbsp;&nbsp;&nbsp;<input type=button value="Create SQL file" onClick="document.backup_form.write_to_file.value = '1'; document.backup_form.submit();">
		{else:}
		<table border=0 cellpadding=2 cellspacing=2>
		<tr>
		<td valign=top>&nbsp;&nbsp;&nbsp;<b>Note:</b></td>
		<td>
		You cannot save database data to a file on the web server ('var/backup/sqldump.sql.php').<br>
		{if:!dirExists}
		The directory 'var/backup/' does not exist or is not writable.
		{else:}
		The file 'var/backup/sqldump.sql.php' is not writable.
		{end:}
		</td>
		</tr>
		</table>
		{end:}
		</td>
		<td>{if:fileExists}&nbsp;&nbsp;&nbsp;<input type="button" "delete" value="Delete SQL file" onclick="document.backup_form.action.value='delete'; document.backup_form.submit();">{end:}</td>
	</tr>
	<tr>
		<td colspan='3'>&nbsp;</td>
	</tr>	
</table>
<p align="justify">
	<b>Note:</b> if you store product images in the database, they will be included in the SQL dump file. If product images are located on the file system, they are not included. To backup such images you need to download them directly from the server.
</p>
</form>
