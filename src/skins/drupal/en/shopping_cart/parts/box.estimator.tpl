{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping estimator box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.panel.box", weight="10")
 *}
<div class="estimator">
  {if:isShippingEstimate()}

    <ul>
      <li><span>{t(#Shipping#)}:</span> {cart.shippingMethod.getName():h} ({formatPrice(getShippingCost(),cart.getCurrency())})</li>
      <li><span>{t(#Estimated for#)}:</span> {getEstimateAddress()}</li>
    </ul>

    <div class="link">
      <a href="{buildUrl(#shipping_estimate#)}" class="estimate">{t(#Change method#)}</a>
    </div>

  {else:}

    <widget class="\XLite\View\Form\Cart\ShippingEstimator\Open" name="shippingEstimator" />
      <div class="buttons">
        <widget class="\XLite\View\Button\Submit" label="Estimate shipping cost" style="action estimate" />
      </div>
    <widget name="shippingEstimator" end />

  {end:}

</div>
