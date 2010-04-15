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
<table border="0" cellpadding="6">
<form action="admin.php" method="POST" name="ecard_form">
<input type="hidden" name="target" value="gift_certificate_ecards">
<input type="hidden" name="action" value="update">
<input type="hidden" name="ecard_id" value="">
<tbody IF="ecards">
<tr><th>Pos.</th><th>Thumbnail</th><th>Active</th></tr>
<tr FOREACH="ecards,ecard">
    <td>
        <input type="text" name="pos[{ecard.ecard_id}]" size="4" value="{ecard.order_by}">
    </td>
    <td>
        <a href="admin.php?target=gift_certificate_ecard&ecard_id={ecard.ecard_id}"><img src="{ecard.thumbnail.url:h}" border="0"></a>
    </td>
    <td>
        <input type="checkbox" name="enabled[{ecard.ecard_id}]" checked="{ecard.enabled}">
    </td>
    <td>
        <a href="admin.php?target=gift_certificate_ecard&ecard_id={ecard.ecard_id}"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Edit</a>&nbsp;&nbsp;&nbsp;<a href="admin.php?target=gift_certificate_ecards&ecard_id={ecard.ecard_id}&action=delete"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Delete</a>
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
</tbody>
<tr><td colspan="4" align="center">
<a href="javascript:document.ecard_form.submit()" IF="ecards"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Update</a>&nbsp;&nbsp;&nbsp;<a href="admin.php?target=gift_certificate_ecard"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Add new e-Card</a>
</td></tr>
</form>
</table>
