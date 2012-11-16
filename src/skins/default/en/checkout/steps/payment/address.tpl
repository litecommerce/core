{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Billing adddress
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<widget class="\XLite\View\Form\Checkout\UpdateProfile" name="billingAddress" className="address billing-address" />

  <div class="same-address" IF="isSameAddressVisible()">
    <input type="hidden" name="same_address" value="0" />
    <input id="same_address" type="checkbox" name="same_address" value="1" checked="{isSameAddress()}" />
    <label for="same_address">{t(#The same as shipping#)}</label>
  </div>

  {if:!isEditableAddress()}
    <widget template="checkout/parts/address.plain.tpl" address="{getSameAddress()}" />

  {else:}
    <ul class="form">
      <li FOREACH="getAddressFields(),fieldName,fieldData" class="item-{fieldName}">
        <widget
          class="{fieldData.class}"
          attributes="{getFieldAttributes(fieldName,fieldData)}"
          label="{fieldData.label}"
          fieldName="billingAddress[{fieldName}]"
          stateSelectorId="billingaddress-state-id"
          stateInputId="billingaddress-custom-state"
          value="{getFieldValue(fieldName)}"
          required="{fieldData.required}" />
      </li>
      <list name="checkout.payment.address" address="{getSameAddress()}" />
    </ul>

    {if:!isAnonymous()}
      <hr />

      <div class="save">
        <input type="checkbox" id="save_billing_address" name="billingAddress[save_as_new]" value="1" />
        <label for="save_billing_address">{t(#Save as new#)}</label>
      </div>
    {end:}

  {end:}

<widget name="billingAddress" end />
