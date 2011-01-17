{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Success checkout
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="order-success-box">
  <p><widget template="checkout/success_message.tpl" /></p>
  <div class="buttons-row">
    <widget class="\XLite\View\Button\Link" label="Continue shopping" location="{getContinueURL()}" />
    &nbsp;&nbsp;
    <widget class="\XLite\View\Button\Link" label="Print invoice" location="#" jsCode="window.print(); return false;" />
  </div>

  <hr class="tiny" />

  <widget class="\XLite\View\Invoice" order="{getOrder()}" />
</div>
