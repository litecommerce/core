{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout widget body
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget mode="paymentMethod" template="checkout/payment_methods_step.tpl" />
<widget mode="register" template="checkout/register_step.tpl" />
<widget mode="details" template="checkout/details_step.tpl" />

<widget mode="notAllowed" template="checkout/not_allowed.tpl" head="Checkout not allowed" />
<widget mode="noShipping" template="checkout/no_shipping.tpl" head="No shipping method available" />
<widget mode="noPayment" template="checkout/no_payment.tpl" head="No payment method available" />
<widget mode="error" template="checkout/failure.tpl" head="Checkout error" />
