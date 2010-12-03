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
Co./Last Name{delimiter}First Name{delimiter}Deposit Account{delimiter}Invoice #{delimiter}Invoice Date{delimiter}Amount Applied{crlf}
{foreach:orders,oid,order}{if:order.processed}{order.order_id}: {order.profile.billing_lastname}{delimiter}{order.profile.billing_firstname}{delimiter}{deposit_account}{delimiter}{order.order_id}{delimiter}{date_format(order,#date#)}{delimiter}{order.total}{crlf}{end:}{end:}
