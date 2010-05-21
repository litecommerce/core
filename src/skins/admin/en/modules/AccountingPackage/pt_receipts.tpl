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
Deposit Ticket ID,Customer ID,Customer Name,Reference,Date,Payment Method,Cash Account,Cash Amount,Sales Representative ID,Sales Tax Code,Total Paid on Invoice(s),Discount Account,Prepayment,Vendor Receipt,AR Date Cleared in Bank Rec,Number of Distributions,Invoice Paid,Discount Amount,Quantity,Item ID,Description,G/L Account,Unit Price,Tax Type,Amount,Job ID,Sales Tax Authority,Transaction Period,Transaction Number,Receipt Number,Card/Check Holder's Name,Card/Check Address Line 1,Card/Check Address Line 2,Card/Check City,Card/Check State,Card/Check Zip Code,Card/Check Country,Credit Card Number,Credit Card Expiration Date,Card/Check Auth. Code,Card/Check Authorized Status,Credit Card Comment,Authorized Amount{crlf}
{foreach:orders,order}
{if:order.processed}
,CUST-{if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:},,{order.order_id},{date_format(order,#date#)},{order.paymentMethod.name:h},{cash_account},{order.total},,,-{order.total},,FALSE,FALSE,,1,{order.order_id},0.00,0.00,,,,0.00,0,-{order.total},,,24,{order.order_id},,,,,,,,,,,,0,,0.00{crlf}
{end:}
{end:}
