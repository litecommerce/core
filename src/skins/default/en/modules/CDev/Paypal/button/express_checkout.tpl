{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * PayPal Express Checkout button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<button type="button" onclick="javascript: {getJSCode():h}" class="{getClass()}"{if:getId()} id="{getId()}"{end:} {if:isDisabled()} disabled="disabled" {end:}>
  <img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" align="left" style="margin-right:7px;">
</button>
