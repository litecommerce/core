{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : payment step : selected state : address
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="checkout.payment.selected", weight="20")
 *}
<div class="secondary">
  <h3>{t(#Billing address#)}</h3>
  <widget IF="!isAnonymous()" class="\XLite\View\Button\Link" label="Address book" location="{buildURL(#select_address#,##,_ARRAY_(#atype#^#b#))}" style="address-book" />

  <widget class="\XLite\View\Checkout\BillingAddress" />
</div>
