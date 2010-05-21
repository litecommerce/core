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
<tbody IF="item.gcid">
    <tr><td>Recipient:</td><td>{item.gc.recipient}</td></tr>
    <tr IF="item.gc.send_via=#E#)"><td>Recipient email:</td><td>{item.gc.recipient_email}</td></tr>
    <tr IF="item.gc.send_via=#P#)"><td>Mail address:</td><td>
{item.gc.recipient_firstname} {item.gc.recipient_lastname}<br>
{item.gc.recipient_address}, {item.gc.recipient_city},<br>
{if:!item.gc.recipient_state=-1)}{item.gc.recipientState.state}{end:} {item.gc.recipientCountry.country}, {item.gc.recipient_zipcode}
	</td></tr>
	<tr><td>Message:</td><td>{item.gc.message}</td></tr>
</tbody>
