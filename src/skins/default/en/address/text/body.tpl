{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<ul class="address-entry">

{foreach:getSchemaFields(),fieldName,fieldData}

<li class="address-text-cell address-text-{fieldName}" IF="{getFieldValue(fieldName)}">

  <ul class="address-text">

    <li class="address-text-label address-text-label-{fieldName}">{fieldData.label}:</li>

    <li class="address-text-value">{getFieldValue(fieldName,1)}</li>

    <li class="address-text-comma address-text-comma-{fieldName}">,</li>

  </ul>

</li>

{end:}

</ul>

<div class="clear"></div>
