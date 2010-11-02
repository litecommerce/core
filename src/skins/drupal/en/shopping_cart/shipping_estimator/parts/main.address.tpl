{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping estimator : address
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="shippingEstimator.main", weight="10")
 *}
<widget class="\XLite\View\Form\Cart\ShippingEstimator\Destination" name="destination" className="estimator" />

  <ul class="form">
    {displayViewListContent(#shippingEstimator.address#)}
  </ul>

  <div IF="isEstimate()" class="buttons">
    <widget class="\XLite\View\Button\Submit" label="Apply destination" />
  </div>

  <div IF="!isEstimate()" class="buttons main">
    <widget class="\XLite\View\Button\Submit" label="Apply destination" style="action"/>
  </div>

<widget name="destination" end />

