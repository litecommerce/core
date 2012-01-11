{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping estimator : methods
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="shippingEstimator.main", weight="20")
 *}
<div IF="isEstimate()" class="estimate-methods">
  <h3>{t(#Choose shipping method#)}</h3>

  <widget class="\XLite\View\Form\Cart\ShippingEstimator\Change" name="change" />

    {if:modifier.getRates()}
      <widget class="\XLite\View\ShippingList" />

      <div class="buttons main">
        <widget class="\XLite\View\Button\Submit" label="{t(#Choose method#)}" style="action" />
      </div>
    {else:}
      <p class="error">{t(#Shipping methods are not available#)}</p>
    {end:}

  <widget name="change" end />

</div>
