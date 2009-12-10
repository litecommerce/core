Use this section to manage your store's image files.
<hr><br>
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="CenterBorder">
			<table border="0" cellspacing="1" cellpadding="3">
				<tr class="TableHead">
					<th>&nbsp;</th>
					<th>Naming schema</th>
					<th>File system</th>
					<th>Database</th>
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
				</tr>
			</table>
	    </td>
	</tr>
</table>
<br>
<p align="justify">
Image files can either be placed in the <b>'{imagesDir}'</b> sub-directory of your LiteCommerce installation or stored in the database. Using this section you can specify where you want different kinds of images to be located. Storing images in the database makes it easier to backup them, while leaving them as files helps to keep the database more compact.
</p>
<p align="justify">
<b>Note:</b> '??' in the names of image files are replaced with the corresponding category/product identifiers.
</p>
