{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping estimator box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="estimator">

  {if:isShippingEstimate()&modifier.getShippingMethod()}

    <ul>
      <li><span>{t(#Shipping#)}:</span> {modifier.shippingMethod.getName():h} ({getShippingCost()})</li>
      <li><span>{t(#Estimated for#)}:</span> {getEstimateAddress()}</li>
    </ul>

    <div class="link">
      <a href="{buildURL(#shipping_estimate#)}" class="estimate">{t(#Change method#)}</a>
    </div>

  {else:}

    {if:cart.isShippingRatesExists()}
      <widget class="\XLite\View\Form\Cart\ShippingEstimator\Open" name="shippingEstimator" />
        <div class="buttons">
          <widget class="\XLite\View\Button\Submit" label="Estimate shipping cost" style="action estimate" />
        </div>
      <widget name="shippingEstimator" end />

    {else:}
      <span class="error">{t(#Delivery methods is not defined.#)}</span>
      <span class="error">{t(#Shipping is not available.#)}</span>
    {end:}

  {end:}

</div>
