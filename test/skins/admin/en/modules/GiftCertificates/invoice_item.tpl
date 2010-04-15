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
<tbody IF="gcid">
    <tr><td>Recipient:</td><td>{gc.recipient}</td></tr>
    <tr IF="isSelected(gc,#send_via#,#E#)"><td>Recipient email:</td><td>{gc.recipient_email}</td></tr>
    <tr IF="isSelected(gc,#send_via#,#P#)"><td>Mail address:</td><td>
{gc.recipient_firstname} {gc.recipient_lastname}<br>
{gc.recipient_address}, {gc.recipient_city},<br>
{if:!isSelected(gc,#recipient_state#,#-1#)}{gc.recipient_state_name}{end:} {gc.recipient_country_name}, {gc.recipient_zipcode}
	</td></tr>
	<tr><td>Message:</td><td>{gc.message}<br><a href="admin.php?target=gift_certificate&gcid={gcid}" target="_blank"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> See details...</a></td></tr>
</tbody>
