{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout details payment method block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="chekout.details", weight="60")
 *}
<div IF="cart.getPaymentMethod()" class="payment-method">
  <widget class="\XLite\View\Button\Link" label="Change payment method" style="change" location="{buildURL(#checkout#,##,_ARRAY_(#mode#^#paymentMethod#))}" />
  <h2>{cart.paymentMethod.name}</h2>
  <widget template="{getDir()}/paymentForm.tpl" />
</div>
