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
Co./Last Name{delimiter}First Name{delimiter}Card ID{delimiter}Addr 1 - Line 1{delimiter}           - City{delimiter}           - State{delimiter}           - ZIP Code{delimiter}           - Country{delimiter}           - Phone # 1{delimiter}           - Fax #{delimiter}           - Email{delimiter}           - Contact Name{delimiter}           - Salutation{delimiter}Addr 2 - Line 1{delimiter}           - City{delimiter}           - State{delimiter}           - ZIP Code{delimiter}           - Country{delimiter}           - Phone # 1{delimiter}           - Fax #{delimiter}           - Email{delimiter}           - Contact Name{delimiter}           - Salutation{crlf}
{foreach:orders,order}{order.order_id}: {order.profile.billing_lastname}{delimiter}{order.profile.billing_firstname}{delimiter}CARD{order.order_id}{delimiter}{order.profile.billing_address}{delimiter}{order.profile.billing_city}{delimiter}{order.profile.billingState.code}{delimiter}{order.profile.billing_zipcode}{delimiter}{order.profile.billingCountry.country}{delimiter}{order.profile.billing_phone}{delimiter}{order.profile.billing_fax}{delimiter}{order.profile.login}{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname}{delimiter}{order.profile.billing_title} {order.profile.billing_firstname} {order.profile.billing_lastname}{delimiter}{order.profile.shipping_address}{delimiter}{order.profile.shipping_city}{delimiter}{order.profile.shippingState.code}{delimiter}{order.profile.shipping_zipcode}{delimiter}{order.profile.shippingCountry.country}{delimiter}{order.profile.shipping_phone}{delimiter}{order.profile.shipping_fax}{delimiter}{order.profile.login}{delimiter}{order.profile.shipping_lastname}, {order.profile.shipping_firstname}{delimiter}{order.profile.shipping_title} {order.profile.shipping_firstname} {order.profile.shipping_lastname}{crlf}{end:}
