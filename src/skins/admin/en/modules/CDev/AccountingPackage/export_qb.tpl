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
!CUST{delimiter}NAME{delimiter}BADDR1{delimiter}BADDR2{delimiter}BADDR3{delimiter}SADDR1{delimiter}SADDR2{delimiter}SADDR3{delimiter}EMAIL{delimiter}PHONE1{delimiter}FAXNUM{delimiter}

{* ********** CUSTOMER DETAILS ********** *}
{foreach:orders,order}

CUST{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}
{order.profile.billing_address}{delimiter}{order.profile.billing_city}, {order.profile.billingState.state} {order.profile.billing_zipcode}{delimiter}{order.profile.billingCountry.country}{delimiter}{order.profile.shipping_address}{delimiter}{order.profile.shipping_city}, {order.profile.shippingState.state} {order.profile.shipping_zipcode}{delimiter}{order.profile.shippingCountry.country}{delimiter}
{order.profile.login}{delimiter}{order.profile.billing_phone}{delimiter}{order.profile.billing_fax}{delimiter}

{end:}

{* ********** ORDER TRANSACTION FORMAT ********** *}

!TRNS{delimiter}TRNSTYPE{delimiter}DATE{delimiter}ACCNT{delimiter}NAME{delimiter}CLASS{delimiter}AMOUNT{delimiter}DOCNUM{delimiter}MEMO{delimiter}ADDR1{delimiter}ADDR2{delimiter}ADDR3{delimiter}SHIPVIA{delimiter}SADDR1{delimiter}SADDR2{delimiter}SADDR3{delimiter}

!SPL{delimiter}TRNSTYPE{delimiter}DATE{delimiter}ACCNT{delimiter}NAME{delimiter}CLASS{delimiter}AMOUNT{delimiter}DOCNUM{delimiter}MEMO{delimiter}PRICE{delimiter}QNTY{delimiter}INVITEM{delimiter}TAXABLE{delimiter}EXTRA

!ENDTRNS{delimiter}


{* ********** ORDER DETAILS ********** *}

{foreach:orders,order}

TRNS{delimiter}INVOICE{delimiter}{date_format(order,#date#,config.AccountingPackage.qb_date_format)}{delimiter}Accounts Receivable{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}Website:Retail{delimiter}{order.total}{delimiter}{order.order_id}{delimiter}Website Order: {order.detailsString}{delimiter}{order.profile.billing_address}{delimiter}{order.profile.billing_city}, {order.profile.billingState.state} {order.profile.billing_zipcode}{delimiter}{order.profile.billingCountry.country}{delimiter}{delimiter}{order.profile.shipping_address}{delimiter}{order.profile.shipping_city}, {order.profile.shippingState.state} {order.profile.shipping_zipcode}{delimiter}{order.profile.shippingCountry.country}{delimiter}

{* ********** ORDER: shipping details ********** *}

SPL{delimiter}INVOICE{delimiter}{date_format(order,#date#,config.AccountingPackage.qb_date_format)}{delimiter}Sales:Shipping{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}Website:Retail{delimiter}-{order.shipping_cost}{delimiter}{order.order_id}{delimiter}{order.shippingMethod.name:h}{delimiter}{order.shipping_cost}{delimiter}-1{delimiter}SHIPPING{delimiter}N{delimiter}

{if:found(order,#discount#)}
{* ********** ORDER: discount ********** *}

SPL{delimiter}INVOICE{delimiter}{date_format(order,#date#,config.AccountingPackage.qb_date_format)}{delimiter}Sales:Coupon Discount{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}Website:Retail{delimiter}{order.discount}{delimiter}{order.order_id}{delimiter}{order.discountCoupon}{delimiter}-{order.discount}{delimiter}-1{delimiter}COUPON DISCOUNT{delimiter}N{delimiter}
{end:}

{if:found(order,#payedByPoints#)}
{* ********** ORDER: bonus points discount ********** *}

SPL{delimiter}INVOICE{delimiter}{date_format(order,#date#,config.AccountingPackage.qb_date_format)}{delimiter}Sales:Bonus Points Discount{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}Website:Retail{delimiter}{order.payedByPoints}{delimiter}{order.order_id}{delimiter}Bonus Points{delimiter}-{order.payedByPoints}{delimiter}-1{delimiter}BONUS POINTS DISCOUNT{delimiter}N{delimiter}
{end:}

{if:found(order,#payedByGC#)}
{* ********** ORDER: gift certificate ********** *}

SPL{delimiter}INVOICE{delimiter}{date_format(order,#date#,config.AccountingPackage.qb_date_format)}{delimiter}Sales:Gift Certificate{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}Website:Retail{delimiter}{order.payedByPoints}{delimiter}{order.order_id}{delimiter}{gcid}{delimiter}-{order.payedByGC}{delimiter}-1{delimiter}GIFT CERTIFICATE{delimiter}N{delimiter}
{end:}

{if:order.tax}
{* ********** ORDER: total tax applied ********** *}

SPL{delimiter}INVOICE{delimiter}{date_format(order,#date#,config.AccountingPackage.qb_date_format)}{delimiter}Sales:Tax{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}Website:Retail{delimiter}-{order.tax}{delimiter}{order.order_id}{delimiter}Tax{delimiter}{order.tax}{delimiter}-1{delimiter}TAX{delimiter}N{delimiter}
{end:}

{* ********** ORDER: additional properties ********** *}

{* ********** ORDER: order item details ********** *}
<widget template="modules/CDev/AccountingPackage/product_qb.tpl">

{if:order.tax}
{* ********** TAX issue - make QB happy ********** *}

SPL{delimiter}INVOICE{delimiter}{date_format(order,#date#,config.AccountingPackage.qb_date_format)}{delimiter}Sales:Tax{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}Website:Retail{delimiter}0{delimiter}{order.order_id}{delimiter}TAX{delimiter}{delimiter}{delimiter}{delimiter}N{delimiter}AUTOSTAX
{end:}

ENDTRNS{delimiter}

{end:}

{* ********** PROCESSED ORDERS ********** *}

!TRNS{delimiter}TRNSTYPE{delimiter}DATE{delimiter}ACCNT{delimiter}NAME{delimiter}AMOUNT{delimiter}PAYMETH{delimiter}DOCNUM
!SPL{delimiter}TRNSTYPE{delimiter}DATE{delimiter}ACCNT{delimiter}NAME{delimiter}AMOUNT{delimiter}DOCNUM
!ENDTRNS{delimiter}

{foreach:orders,order}
{if:order.processed}

TRNS{delimiter}PAYMENT{delimiter}{date_format(order,#date#,config.AccountingPackage.qb_date_format)}{delimiter}Undeposited Funds{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}{order.total}{delimiter}{order.paymentMethod.name:h}{delimiter}{order.order_id}

SPL{delimiter}PAYMENT{delimiter}{date_format(order,#date#,config.AccountingPackage.qb_date_format)}{delimiter}Accounts Receivable{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}-{order.total}{delimiter}{order.order_id}

ENDTRNS

{end:}
{end:}
