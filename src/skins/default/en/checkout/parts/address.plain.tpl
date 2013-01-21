{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Plain address block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<ul class="address-box">
  {foreach:getAddressFields(),fieldName,fieldData}
  <li class="address-text-cell address-text-{fieldName}" IF="{getFieldValue(fieldName,address)}">
    <ul class="address-text">
      <li class="address-text-label address-text-label-{fieldName}">{fieldData.label}:</li>
      <li class="address-text-value">{getFieldValue(fieldName,address,1)}</li>
      <li class="address-text-comma address-text-comma-{fieldName}">,</li>
    </ul>
  </li>
  {end:}
</ul>

<div class="clear"></div>
