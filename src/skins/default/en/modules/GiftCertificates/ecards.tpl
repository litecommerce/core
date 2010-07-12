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
<table border="0" width="100%">
<form action="cart.php" method="POST" name="ecard_form">
<input type="hidden" name="target" value="gift_certificate_ecards">
<input type="hidden" name="action" value="update">
<input type="hidden" name="gcid" value="{gcid}">
<input type="hidden" name="ecard_id" value="">
<tr FOREACH="split(ecards,3),row">
    <td FOREACH="row,ecard" align="center" width="33%">
        <span IF="ecard">
        <a href="{ecard.image.url}" target="_blank"><img src="{ecard.thumbnail.url}" border="0"></a><br>
        <widget class="\XLite\View\Button" label="Select" href="javascript: document.ecard_form.ecard_id.value='{ecard.ecard_id}';document.ecard_form.submit()">
        </span>
        <br>&nbsp;
    </td>
</tr>
</form>
</table>
<widget class="\XLite\View\Submit" href="cart.php?target=gift_certificate&gcid={gcid}" label="Cancel">
