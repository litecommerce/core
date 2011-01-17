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
Co./Last Name{delimiter}First Name{delimiter}Journal Number{delimiter}Date{delimiter}Payment Method{delimiter}Memo{delimiter}Description{delimiter}Account Number{delimiter}Amount{delimiter}Sale Status{delimiter}{crlf}
{foreach:orders,oid,order}
{order.order_id}: {order.profile.billing_lastname}{delimiter}{order.profile.billing_firstname}{delimiter}{order.order_id}{delimiter}{formatDate(order,#date#)}{delimiter}{order.paymentMethod.name}{delimiter}Sale; {order.profile.billing_title} {order.profile.billing_firstname} {order.profile.billing_lastname}{delimiter}SHIPPING: {order.shippingMethod.name:h}{delimiter}{income_account}{delimiter}{order.shipping_cost}{delimiter}I{delimiter}{crlf}
{if:order.tax}{order.order_id}: {order.profile.billing_lastname}{delimiter}{order.profile.billing_firstname}{delimiter}{order.order_id}{delimiter}{formatDate(order,#date#)}{delimiter}Sale; {order.profile.billing_title} {order.profile.billing_firstname} {order.profile.billing_lastname}{delimiter}TAX{delimiter}{income_account}{delimiter}{order.tax}{delimiter}I{delimiter}{crlf}{end:}
{if:found(order,#discount#)}{order.order_id}: {order.profile.billing_lastname}{delimiter}{order.profile.billing_firstname}{delimiter}{order.order_id}{delimiter}{formatDate(order,#date#)}{delimiter}Sale; {order.profile.billing_title} {order.profile.billing_firstname} {order.profile.billing_lastname}{delimiter}COUPON DISCOUNT: {order.discountCoupon}{delimiter}{income_account}{delimiter}-{order.discount}{delimiter}I{delimiter}{crlf}{end:}
{if:found(order,#payedByPoints#)}{order.order_id}: {order.profile.billing_lastname}{delimiter}{order.profile.billing_firstname}{delimiter}{order.order_id}{delimiter}{formatDate(order,#date#)}{delimiter}Sale; {order.profile.billing_title} {order.profile.billing_firstname} {order.profile.billing_lastname}{delimiter}BONUS POINTS DISCOUNT{delimiter}{income_account}{delimiter}-{order.payedByPoints}{delimiter}I{delimiter}{crlf}{end:}
{if:found(order,#payedByGC#)}{order.order_id}: {order.profile.billing_lastname}{delimiter}{order.profile.billing_firstname}{delimiter}{order.order_id}{delimiter}{formatDate(order,#date#)}{delimiter}Sale; {order.profile.billing_title} {order.profile.billing_firstname} {order.profile.billing_lastname}{delimiter}GIFT CERTIFICATE: {gcid}{delimiter}{income_account}{delimiter}-{order.payedByGC}{delimiter}I{delimiter}{crlf}{end:}
{foreach:order.items,iid,item}{order.order_id}: {order.profile.billing_lastname}{delimiter}{order.profile.billing_firstname}{delimiter}{order.order_id}{delimiter}{formatDate(order,#date#)}{delimiter}Sale; {order.profile.billing_title} {order.profile.billing_firstname} {order.profile.billing_lastname}{delimiter}#{item.product_id}: {truncate(item,#name#,#200#):h} QTY: {item.amount}{delimiter}{income_account}{delimiter}{item.total}{delimiter}I{delimiter}{crlf}{end:}
{end:}
