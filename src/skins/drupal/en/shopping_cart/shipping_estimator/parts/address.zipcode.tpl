{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping estimator : address : zipcode
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="shippingEstimator.address", weight="30")
 *}
<li class="zipcode">
  <label for="destination_zipcode">{t(#ZIP-code#)}</label>
  <input name="zipcode" id="destination_zipcode" value="{getZipcode()}" class="field-required field-zipcode" />
</li>
