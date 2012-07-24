{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout failed
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="order-success-box">
  <div class="order-success-panel">
    <p><widget template="checkout/failed_message.tpl" /></p>
    <div class="buttons-row">
      <widget class="\XLite\View\Button\Link" label="Re-order" location="{getReorderURL()}" />
      &nbsp;&nbsp;
      <widget class="\XLite\View\Button\Link" label="Continue shopping" location="{getContinueURL()}" />
    </div>
  </div>
</div>
