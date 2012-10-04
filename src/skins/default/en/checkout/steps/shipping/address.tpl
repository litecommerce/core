{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout shipping address form
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<ul class="form">
  <li FOREACH="getAddressFields(),fieldName,fieldData" class="item-{fieldName}">
    <widget
      class="{fieldData.class}"
      attributes="{getFieldAttributes(fieldName,fieldData)}"
      label="{fieldData.label}"
      fieldName="shippingAddress[{fieldName}]"
      stateSelectorId="shippingaddress-state-id"
      stateInputId="shippingaddress-custom-state"
      value="{getFieldValue(fieldName)}"
      required="{fieldData.required}" />
  </li>
  <list name="checkout.shipping.address" address="{getShippingAddress()}" />
</ul>
