{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment method
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<form action="admin.php" method="POST" name="payment_methods">
  <input type="hidden" name="target" value="payment_method">
  <input type="hidden" name="action" value="update">
  <input type="hidden" name="method_id" value="{method_id}">

  {if:isWidgetSettings()}
    <widget widget="{paymentMethod.processor.getSettingsWidget()}" paymentMethod="{getPaymentMethod()}" />
  {else:}
    <widget template="{paymentMethod.processor.getSettingsWidget()}" />
  {end:}

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" label="Update" />
  </div>

</form>
