{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment method
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<form action="admin.php" method="post" name="payment_settings">
  <input type="hidden" name="target" value="payment_method" />
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="method_id" value="{method_id}" />

  {if:isWidgetSettings()}
    <widget class="{paymentMethod.processor.getSettingsWidget()}" paymentMethod="{getPaymentMethod()}" />
  {else:}
    <widget template="{paymentMethod.processor.getSettingsWidget()}" />
  {end:}

  <div IF="{paymentMethod.processor.useDefaultSettingsFormButton()}" class="buttons">
    <widget class="\XLite\View\Button\Submit" label="{t(#Update#)}" />
  </div>

</form>
