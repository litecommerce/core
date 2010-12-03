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
<span class="ErrorMessage">{error}</span>

<form action="admin.php" method="POST">
<input type="hidden" name="target" value="xcart_import">
<input type="hidden" name="action" value="import">

<table border="0" width="80%">
<tr><td colspan="2">
	<span class="AdminTitle">X-Cart database connection parameters:</span>
<hr>
</td></tr>
<tr><td align="right" width="20%">Host:</td><td><input type="text" name="hostspec" value="{hostspec:r}" size="30"></td></tr>
<tr><td align="right">Database:</td><td><input type="text" name="database" value="{database:r}" size="30"></td></tr>
<tr><td align="right">Login:</td><td><input type="text" name="username" value="{username:r}" size="30"></td></tr>
<tr><td align="right">Password:</td><td><input type="password" name="password" value="{password:r}" size="30"></td></tr>
<tr><td colspan="2">
	If you do not have any available database connection, you can backup your X-Cart database using X-Cart admin interface and restore the backup file using the "DB Backup/Restore" menu. After that, use this dialog to import data from the local database.
</td></tr>

<tr><td colspan="2">
	<span class="AdminTitle">X-Cart database version:</span>
<hr>
</td></tr>
<tr><td align="right">Version 3.X:</td><td><input type="radio" name="db_version" checked="{isSelected(db_version,#3#)}" value=3 onClick="this.blur()"></td></tr>
<tr><td align="right">Version 4.0.X:</td><td><input type="radio" name="db_version" checked="{isSelected(db_version,#4#)}" value="4" onClick="this.blur()"></td></tr>
<tr><td align="right">Version 4.1.X:</td><td><input type="radio" name="db_version" checked="{isSelected(db_version,#41#)}" value="41" onClick="this.blur()"></td></tr>

<tr><td colspan="2">
	<span class="AdminTitle">Import options:</span>
<hr>
</td></tr>
<tr><td align="right">Import products and categories:</td><td><input type="checkbox" name="import_catalog" checked="{import_catalog}" onClick="this.blur()"></td></tr>
<tr><td align="right">Import users:</td><td><input type="checkbox" name="import_users" checked="{import_users}" onClick="this.blur()"></td></tr>
<tr><td align="right"><b>Note:</b></td><td>Only the images stored in the database will be imported.</td></tr>
<tr><td align="right"></td><td><input type="submit" value=" Import "></td></tr>
</table>
