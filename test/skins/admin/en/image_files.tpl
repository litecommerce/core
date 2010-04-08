Use this section to manage your store's image files.
<hr>
<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<TR>
    <TD colspan=2>
        <table cellspacing=0 cellpadding=0 border=0 width="100%">
        <tr>
            <td colspan=2>&nbsp;</td>
        </tr>
        <tr>
            <td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Images location&nbsp;&nbsp;&nbsp;</td>
            <td width=100%>&nbsp;</td>
        </tr>
        <tr>
            <td class="SidebarTitle" align=center colspan=2 height=3></td>
        </tr>
        </table>
    </TD>
</TR>
</table>
<br />
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="CenterBorder">
			<table border="0" cellspacing="1" cellpadding="3">
				<tr class="TableHead">
					<th>&nbsp;</th>
					<th>Naming schema</th>
					<th>File system</th>
					<th>Database</th>
					<th>Default location</th>
				</tr>
				<tr FOREACH="imageClasses,className,imageClass" class="DialogBox">
					<td>{imageClass.comment}</td>
					<td>{imageClass.image.createFileName(#??#)}</td>
					<td align="center">{imageClass.image.filesystemCount}
						<table border=0>
							<form action="admin.php" method="POST" IF="imageClass.image.filesystemCount" style="top-margin: 0">
							<input type="hidden" name="target" value="image_files">
							<input type="hidden" name="action" value="move_to_database">
							<input type="hidden" name="index" value="{className}">
							<tr>
								<td><input type="submit" value="Move to database"></td>
							</tr>
							</form>
						</table>
					</td>
					<td align="center">{imageClass.image.databaseCount}
						<table border=0>
							<form action="admin.php" method="POST" IF="imageClass.image.databaseCount">
							<input type="hidden" name="target" value="image_files">
							<input type="hidden" name="action" value="move_to_filesystem">
							<input type="hidden" name="index" value="{className}">
							<tr>
								<td><input type="submit" value="Move to filesystem"></td>
							</tr>
							</form>
						</table>
					</td>
					<td>
						<table border="0">
							<form action="admin.php" method="POST">
							<input type="hidden" name="target" value="image_files">
							<input type="hidden" name="action" value="update_default_source">
							<input type="hidden" name="index" value="{className}">
							<tr>
								<td>
								<select name="default_source">
								<option value="D" selected="imageClass.image.defaultSource=#D#">Database</option>
								<option value="F" selected="imageClass.image.defaultSource=#F#">File system</option>
								</select>
								</td>
								<td><input type="submit" value="Update"></td>
							</tr>
							</form>
						</table>
					</td>
				</tr>
			</table>
	    </td>
	</tr>
</table>
<br>
<p align="justify">
Image files can either be placed in the <i><b>{imagesDir}</b></i> sub-directory of your LiteCommerce installation or stored in the database. Using this section you can specify where you want different kinds of images to be located. Storing images in the database makes it easier to backup them, while leaving them as files helps to keep the database more compact.
</p>
<p align="justify">
<b>Note:</b> '??' in the names of image files are replaced with the corresponding category/product identifiers.
</p>

<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<TR>
    <TD colspan=2>
        <table cellspacing=0 cellpadding=0 border=0 width="100%">
        <tr>
            <td colspan=2>&nbsp;</td>
        </tr>
        <tr>
            <td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Images directory&nbsp;&nbsp;&nbsp;</td>
            <td width=100%>&nbsp;</td>
        </tr>
        <tr>
            <td class="SidebarTitle" align=center colspan=2 height=3></td>
        </tr>
        </table>
    </TD>
</TR>
</table>
<br />
<form name="update_images_dir_form" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}" />
<input type="hidden" name="action" value="update_images_dir" />
Images directory : <input type="text" name="images_dir" value="{imagesDir}" size="30" /><br /><br />
<b>Note:</b> enter the relative path to the directory, where images are stored. For example, if images are stored in the <i><b>images</b></i> sub-directory of your LiteCommerce installation, enter <i><b>images</b></i> in this field. If you don't specify images directory then <i><b>images</b></i> will be used as default.
<br />
<br />
<input type="submit" value=" Update " />
</form>
