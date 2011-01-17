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
"Customer ID","Invoice/CM #","Apply to Invoice Number","Credit Memo","Date","Ship By","Quote","Quote #","Quote Good Thru Date","Drop Ship","Ship to Name","Ship to Address-Line One","Ship to Address-Line Two","Ship to City","Ship to State","Ship to Zipcode","Ship to Country","Customer PO","Ship Via","Ship Date","Date Due","Discount Amount","Discount Date","Displayed Terms","Sales Representative ID","Accounts Receivable Account","Sales Tax Code","Invoice Note","Note Prints After Line Items","Statement Note","Stmt Note Prints Before Ref","Internal Note","Beginning Balance Transaction","Number of Distributions","Invoice/CM Distribution","Apply to Invoice Distribution","Apply To Sales Order","Quantity","SO Number","Item ID","SO Distribution","Description","G/L Account","Unit Price","Tax Type","Amount","Job ID","Sales Tax Authority","Transaction Period","Transaction Number","Return Authorization"{crlf}
{foreach:orders,oid,order}
{addDistribution(order,#shipping#)}
{if:order.tax}{addDistribution(order,#tax#)}{end:}
{if:found(order,#discount#)}{addDistribution(order,#discount#)}{end:}
{if:found(order,#payedByPoints#)}{addDistribution(order,#payedByPoints#)}{end:}
{if:found(order,#payedByGC#)}{addDistribution(order,#payedByGC#)}{end:}
{foreach:order.items,iid,item}
{addDistribution(order)}
{end:}
{end:}
{foreach:orders,oid,order}
"CUST-{if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}","{order.order_id}","","FALSE","{formatDate(order,#date#)}","","FALSE","","","FALSE","","{CSVQuoting(order.profile.shipping_address)}","","{CSVQuoting(order.profile.shipping_city)}","{CSVQuoting(order.profile.shippingState.code)}","{CSVQuoting(order.profile.shipping_zipcode)}","{CSVQuoting(order.profile.shippingCountry.country)}","","{CSVQuoting(order.shippingMethod.name):h}","","{getDateDue(#1224680962#)}","","","","","{receivable_account}","","","FALSE","","FALSE","","FALSE","{getTotalDistribution(order)}","{getCurrentDistribution(order)}","0","FALSE","1","","","0","SHIPPING: {order.shippingMethod.name:h}","{CSVQuoting(sales_account)}","{price_format(order.shipping_cost):h}","1","-{price_format(order.shipping_cost):h}","","","","",""{crlf}
{if:order.tax}
"CUST-{if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}","{order.order_id}","","FALSE","{formatDate(order,#date#)}","","FALSE","","","FALSE","","{CSVQuoting(order.profile.shipping_address)}","","{CSVQuoting(order.profile.shipping_city)}","{CSVQuoting(order.profile.shippingState.code)}","{CSVQuoting(order.profile.shipping_zipcode)}","{CSVQuoting(order.profile.shippingCountry.country)}","","{CSVQuoting(order.shippingMethod.name):h}","","{getDateDue(#1224680962#,#%m/%d/%y#)}","","","","","{receivable_account}","","","FALSE","","FALSE","","FALSE","{getTotalDistribution(order)}","{getCurrentDistribution(order)}","0","FALSE","1","","","0","TAX","{CSVQuoting(sales_account)}","{order.tax}","1","-{order.tax}","","","","",""{crlf}
{end:}
{if:found(order,#discount#)}
"CUST-{if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}","{order.order_id}","","FALSE","{formatDate(order,#date#)}","","FALSE","","","FALSE","","{CSVQuoting(order.profile.shipping_address)}","","{CSVQuoting(order.profile.shipping_city)}","{CSVQuoting(order.profile.shippingState.code)}","{CSVQuoting(order.profile.shipping_zipcode)}","{CSVQuoting(order.profile.shippingCountry.country)}","","{CSVQuoting(order.shippingMethod.name):h}","","{getDateDue(#1224680962#,#%m/%d/%y#)}","","","","","{receivable_account}","","","FALSE","","FALSE","","FALSE","{getTotalDistribution(order)}","{getCurrentDistribution(order)}","0","FALSE","1","","","0","COUPON DISCOUNT: {CSVQuoting(order.discountCoupon)}","{CSVQuoting(sales_account)}","{order.discount}","1","{order.discount}","","","","",""{crlf}
{end:}
{if:found(order,#payedByPoints#)}
"CUST-{if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}","{order.order_id}","","FALSE","{formatDate(order,#date#)}","","FALSE","","","FALSE","","{CSVQuoting(order.profile.shipping_address)}","","{CSVQuoting(order.profile.shipping_city)}","{CSVQuoting(order.profile.shippingState.code)}","{CSVQuoting(order.profile.shipping_zipcode)}","{CSVQuoting(order.profile.shippingCountry.country)}","","{CSVQuoting(order.shippingMethod.name):h}","","{getDateDue(#1224680962#,#%m/%d/%y#)}","","","","","{receivable_account}","","","FALSE","","FALSE","","FALSE","{getTotalDistribution(order)}","{getCurrentDistribution(order)}","0","FALSE","1","","","0","BONUS POINTS DISCOUNT","{CSVQuoting(sales_account)}","{order.payedByPoints}","1","{order.payedByPoints}","","","","",""{crlf}
{end:}
{if:found(order,#payedByGC#)}
"CUST-{if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}","{order.order_id}","","FALSE","{formatDate(order,#date#)}","","FALSE","","","FALSE","","{CSVQuoting(order.profile.shipping_address)}","","{CSVQuoting(order.profile.shipping_city)}","{CSVQuoting(order.profile.shippingState.code)}","{CSVQuoting(order.profile.shipping_zipcode)}","{CSVQuoting(order.profile.shippingCountry.country)}","","{CSVQuoting(order.shippingMethod.name):h}","","{getDateDue(#1224680962#,#%m/%d/%y#)}","","","","","{receivable_account}","","","FALSE","","FALSE","","FALSE","{getTotalDistribution(order)}","{getCurrentDistribution(order)}","0","FALSE","1","","","0","GIFT CERTIFICATE: {CSVQuoting(gcid)}","{CSVQuoting(sales_account)}","{order.payedByGC}","1","{order.payedByGC}","","","","",""{crlf}
{end:}
{foreach:order.items,iid,item}
"CUST-{if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}","{order.order_id}","","FALSE","{formatDate(order,#date#)}","","FALSE","","","FALSE","","{CSVQuoting(order.profile.shipping_address)}","","{CSVQuoting(order.profile.shipping_city)}","{CSVQuoting(order.profile.shippingState.code)}","{CSVQuoting(order.profile.shipping_zipcode)}","{CSVQuoting(order.profile.shippingCountry.country)}","","{CSVQuoting(order.shippingMethod.name):h}","","{getDateDue(#1224680962#,#%m/%d/%y#)}","","","","","{receivable_account}","","","FALSE","","FALSE","","FALSE","{getTotalDistribution(order)}","{getCurrentDistribution(order)}","0","FALSE","{item.amount}","","","0","#{item.product_id}: {CSVQuoting(truncate(item,#name#,#150#)):h}","{CSVQuoting(sales_account)}","{item.price}","1","-{item.total}","","","","",""{crlf}
{end:}
{end:}
