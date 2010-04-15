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
<table border=0>
<tr><td colspan="2"><b>Customer information:</b><hr></td></tr>

<tr><td>E-mail:</td><td>{cart.profile.login}</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
</table>
<table border=0 width=80%>
<tr><td valign=top>
<table border=0>
<tr><td colspan="2"><b>Billing information:</b><hr></td></tr>

<tr><td nowrap>First Name:</td><td>{cart.profile.billing_firstname}</td></tr>
<tr><td nowrap>Last Name:</td><td>{cart.profile.billing_lastname}</td></tr>
<tr><td nowrap>Phone:</td><td>{cart.profile.billing_phone}</td></tr>
<tr><td nowrap>Fax:</td><td>{cart.profile.billing_fax}</td></tr>
<tr><td nowrap>Address:</td><td>{cart.profile.billing_address}</td></tr>
<tr><td nowrap>City:</td><td>{cart.profile.billing_city}</td></tr>
<tr><td nowrap>State:</td><td>{cart.profile.billingState.state}</td></tr>
<tr><td nowrap>Country:</td><td>{cart.profile.billingCountry.country}</td></tr>
<tr><td nowrap>Zip code:</td><td>{cart.profile.billing_zipcode}</td></tr>
</table></td>
<td width=10></td><td valign=top>
<table border=0>
<tr> <td colspan="2"><b>Shipping Information:</b><hr></td></tr>

<tr><td nowrap>First Name:</td><td>{cart.profile.shipping_firstname}</td></tr>
<tr><td nowrap>Last Name:</td><td>{cart.profile.shipping_lastname}</td></tr>
<tr><td nowrap>Phone:</td><td>{cart.profile.shipping_phone}</td></tr>
<tr><td nowrap>Fax:</td><td>{cart.profile.shipping_fax}</td></tr>
<tr><td nowrap>Address:</td><td>{cart.profile.shipping_address}</td></tr>
<tr><td nowrap>City:</td><td>{cart.profile.shipping_city}</td></tr>
<tr><td nowrap>State:</td><td>{cart.profile.shippingState.state}</td></tr>
<tr><td nowrap>Country:</td><td>{cart.profile.shippingCountry.country}</td></tr>
<tr><td nowrap>Zip code:</td><td>{cart.profile.shipping_zipcode}</td></tr>
</table></td>
</tr>
</table>

<p /><widget class="XLite_View_Button_Link" label="Modify address information" location="{buildURL(#profile#,##,_ARRAY_(#mode#^#modify#,#returnUrl#^getURL()))}" />
<p /><widget class="XLite_View_Button_Link" label="Change payment method" location="{buildURL(#checkout#,##,_ARRAY_(#mode#^#paymentMethod#))}" />

<p>
<widget template="checkout/credit_card.tpl" visible="{cart.paymentMethod.formTemplate=#checkout/credit_card.tpl#}">
<widget template="checkout/echeck.tpl" visible="{cart.paymentMethod.formTemplate=#checkout/echeck.tpl#}">
<widget template="checkout/offline.tpl" visible="{cart.paymentMethod.formTemplate=#checkout/offline.tpl#}">
<widget module="ePDQ" template="modules/ePDQ/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/ePDQ/checkout.tpl#}">
<widget module="WorldPay" template="modules/WorldPay/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/WorldPay/checkout.tpl#}">
<widget module="GiftCertificates" template="modules/GiftCertificates/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/GiftCertificates/checkout.tpl#}">
<widget module="Promotion" template="modules/Promotion/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/Promotion/checkout.tpl#}">
<widget module="2CheckoutCom" template="modules/2CheckoutCom/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/2CheckoutCom/checkout.tpl#}">
<widget module="PayPal" template="modules/PayPal/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/PayPal/checkout.tpl#}">
<widget module="Nochex" template="modules/Nochex/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/Nochex/checkout.tpl#}">
<widget module="PayPalPro" template="modules/PayPalPro/standard_checkout.tpl" visible="{cart.paymentMethod.params.solution=#standard#}">
<widget module="PayPalPro" template="modules/PayPalPro/express_checkout.tpl" visible="{cart.paymentMethod.payment_method=#paypalpro_express#}">
<widget module="SecureTrading" template="modules/SecureTrading/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/SecureTrading/checkout.tpl#}">
<widget module="ChronoPay" template="modules/ChronoPay/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/ChronoPay/checkout.tpl#}">
<widget module="PayFlowLink" template="modules/PayFlowLink/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/PayFlowLink/checkout.tpl#}">
<widget module="GoogleCheckout" template="modules/GoogleCheckout/google_checkout.tpl" visible="{cart.paymentMethod.payment_method=#google_checkout#}">
<!-- PAYMENT METHOD FORM -->
