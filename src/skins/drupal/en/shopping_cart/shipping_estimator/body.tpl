{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping estimator
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="\XLite\View\Form\Cart\ShippingEstimator\Destination" name="destination" className="estimator" />

  <ul class="form">

    <li>
      <label for="destination_country">{t(#Country#)}</label>
      <select name="country" id="destination_country" class="country">
        <option FOREACH="getCountries(),country" value="{country.code}" selected="{isCountrySelected(country)}">{country.country}</option>
      </select>
    </li>

    <li>
      <label for="destination_zipcode">{t(#ZIP-code#)}</label>
      <input name="zipcode" id="destination_zipcode" value="{getZipcode()}" class="field-required zipcode" />
    </li>

  </ul>

  <div IF="isEstimate()" class="buttons">
    <widget class="\XLite\View\Button\Submit" label="Apply destination" />
  </div>

  <div IF="!isEstimate()" class="buttons main">
    <widget class="\XLite\View\Button\Submit" label="Apply destination" style="action"/>
  </div>

<widget name="destination" end />

<div IF="isEstimate()" class="estimate-methods">
  <h3>{t(#Choose shipping method#)}</h3>

  <widget class="\XLite\View\Form\Cart\ShippingEstimator\Change" name="change" />

    <widget class="\XLite\View\ShippingList" />

    <div class="buttons main">
      <widget class="\XLite\View\Button\Submit" label="Choose method" style="action" />
    </div>

  <widget name="change" end />

</div>


