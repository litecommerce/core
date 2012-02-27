{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Database backup tab template
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
		text-align  : justify;
    }
</style>

{t(#Use this section to back the database of your online store up. Please note that the database backup procedure can take up to several minutes.#)}

<span id="notes_url" style="display:"><a href="javascript:ShowNotes();" class="navigation-path" onclick="this.blur()"><b>{t(#How to back up your store database#)} &gt;&gt;&gt;</b></a></span>
<span id="notes_body" style="display: none">
<p class="adminParagraph">
<table cellpadding="5" cellspacing="0">
    <tr>
        <td>&nbsp;&nbsp;</td>
        <td>&nbsp;&nbsp;</td>
        <td>&nbsp;</td>
        <td>
  {t(#You can choose if to download your database data (SQL dump) directly to your local computer by clicking on the "Download SQL file" button or to save database data to a file on the web server ("var/backup/sqldump.sql.php") by clicking on the "Create SQL file" button#)}.
  {t(#If you choose the second option, you can download the file from the server later on and delete it from the server by clicking on the 'Delete SQL file' button.#)}
        </td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
</table>
</p>
</span>

<hr />

<form action="admin.php" method="post" name="backup_form">
<input type="hidden" name="target" value="db_backup" />
<input type="hidden" name="action" value="backup" />
<input type="hidden" name="write_to_file" value="0" />
<table cellpadding="0" cellspacing="0">
    <tr>
        <td colspan='3'>&nbsp;</td>
    </tr>
	<tr>
        <td><widget class="\XLite\View\Button\Submit" label="{t(#Download SQL file#)}" style="main-button" /></td>
		<td>
		{if:isFileWritable()}&nbsp;&nbsp;&nbsp;<widget class="\XLite\View\Button\Regular" label="{t(#Create SQL file#)}" jsCode="document.backup_form.write_to_file.value = '1'; document.backup_form.submit();" />
		{else:}
		<table cellpadding="2" cellspacing="2">
		<tr>
		<td valign=top>&nbsp;&nbsp;&nbsp;<b>{t(#Note#)}:</b></td>
    <td>
    {t(#You cannot save database data to a file on the web server ('var/backup/sqldump.sql.php').#)}
    <br />
    {if:!dirExists}
    {t(#The directory 'var/backup/' does not exist or is not writable.#)}
    {else:}
    {t(#The file 'var/backup/sqldump.sql.php' is not writable.#)}
		{end:}
		</td>
		</tr>
		</table>
		{end:}
		</td>
		<td IF="isFileExists()">&nbsp;&nbsp;&nbsp;<widget class="\XLite\View\Button\Regular" value="delete" label="{t(#Delete SQL file#)}" jsCode="document.backup_form.action.value='delete'; document.backup_form.submit();" /></td>
	</tr>
	<tr>
		<td colspan='3'>&nbsp;</td>
	</tr>	
</table>
<p align="justify">
  <b>{t(#Note#)}:</b>
  {t(#If you store product images in the database, they are included in the SQL dump file. If the product images are located on the file system, they are not included in the SQL dump file. To back such images up you need to download them directly from the server.#)}
</p>
</form>
