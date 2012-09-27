{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Success checkout
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div class="order-success-box">
  <div class="order-success-panel">
    <p><widget template="checkout/success_message.tpl" /></p>
    <div class="buttons-row">
      <widget class="\XLite\View\Button\Link" label="Continue shopping" location="{getContinueURL()}" />
      &nbsp;&nbsp;
      <widget class="\XLite\View\Button\Link" label="Print invoice" location="#" jsCode="window.print(); return false;" />
    </div>

    <hr class="tiny" />

  </div>

  <widget class="\XLite\View\Invoice" order="{getOrder()}" />
</div>
