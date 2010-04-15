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
Customer ID,Customer Name,Prospect,Inactive,Contact,Bill to Address-Line One,Bill to Address-Line Two,Bill to City,Bill to State,Bill to Zip,Bill to Country,Bill to Sales Tax Code,Ship to Name 1,Ship to Address 1-Line One,Ship to Address 1-Line Two,Ship to City 1,Ship to State 1,Ship to Zipcode 1,Ship to Country 1,Ship to Sales Tax Code 1,Customer Type,Telephone 1,Telephone 2,Fax Number,Customer E-mail,Sales Representative ID,G/L Sales Account,Open Purchase Order Number,Ship Via,Resale Number,Pricing Level,Use Standard Terms,C.O.D. Terms,Prepaid Terms,Terms Type,Due Days,Discount Days,Discount Percent,Credit Limit,Charge Finance Charges,Due Month End Terms,Cardholder's Name,Credit Card Address Line 1,Credit Card Address Line 2,Credit Card City,Credit Card State,Credit Card Zip Code,Credit Card Country,Credit Card Number,Credit Card Expiration Date,Use Receipt Settings,Customer Payment Method,Customer Cash Account,Second Contact,Lawn Care Srvc?,Monthly Service?,Qtrly Mailing?,Referral,Customer Since Date,Last Statement Date,Customer Web Site{crlf}
{foreach:orders,order}
CUST-{if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:},{order.profile.billing_firstname} {order.profile.billing_lastname},FALSE,FALSE,{order.profile.billing_title} {order.profile.billing_firstname} {order.profile.billing_lastname},"{order.profile.billing_address}",,{order.profile.billing_city},{order.profile.billingState.code},{order.profile.billing_zipcode},{order.profile.billingCountry.country},,{order.profile.shipping_title} {order.profile.shipping_firstname} {order.profile.shipping_lastname},"{order.profile.shipping_address}",,{order.profile.shipping_city},{order.profile.shippingState.code},{order.profile.shipping_zipcode},{order.profile.shippingCountry.country},,,{order.profile.billing_phone},,{order.profile.billing_fax},{order.profile.login},,,,0,,0{crlf}
{end:}
