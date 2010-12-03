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
<widget template="{cart.paymentMethod.formTemplate}" IF="cart.paymentMethod.formTemplate" />

<widget module="CDev\PayPalPro" template="modules/CDev/PayPalPro/standard_checkout.tpl" IF="{cart.paymentMethod.params.solution=#standard#}">
<widget module="CDev\PayPalPro" template="modules/CDev/PayPalPro/express_checkout.tpl" IF="{cart.paymentMethod.payment_method=#paypalpro_express#}">
<widget module="CDev\GoogleCheckout" template="modules/CDev/GoogleCheckout/google_checkout.tpl" IF="{cart.paymentMethod.payment_method=#google_checkout#}">
