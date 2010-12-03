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
<a name=order_management></a>
<p>From this page you can encrypt or decrypt all existing order details stored in your database.

<p>Enter Master Password (a GnuPG secret key passphrase) and click on "Encrypt" or "Decrypt" button below.

<form action="admin.php#order_management" method=POST>
<input type=hidden name=target value=advanced_security>
<input type=hidden name=action value=orders>
<table border=0>
<tr>
<tr IF="invalidKeyring"><td colspan=2 class=ErrorMessage>Invalid keyring</td></tr>
<td>Master password:</td><td><input type=password name=passphrase size=32></td>
</tr>
<tr IF="invalidOrderPassword"><td>&nbsp;</td><td class=ErrorMessage>Invalid password</td></tr>
<tr><td>&nbsp;</td></td><td>* required only for data decryption.</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2><input type=submit name=encrypt_orders value=Encrypt> <input type=submit name=decrypt_orders value=Decrypt> 
</table>
</form>
