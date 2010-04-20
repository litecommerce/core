{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment methods details forms switcher
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
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
