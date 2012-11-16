{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping estimator : address : state
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="shippingEstimator.address", weight="20")
 *}
<li class="state" IF="hasField(#state_id#)">
  <label for="destination_state">{t(#State#)}:</label>
  <widget class="\XLite\View\StateSelect" field="state" fieldId="destination_state" state="{getState()}" isLinked="1" className="field-required" />
</li>
