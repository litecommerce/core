{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping estimator : address : country
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="shippingEstimator.address", weight="10")
 *}
<li class="country">
  <label for="destination_country">{t(#Country#)}</label>
  <widget class="\XLite\View\CountrySelect" field="country" fieldId="destination_country" country="{getCountryCode()}" />
</li>
