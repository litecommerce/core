{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Add / update gift certificate
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

{*
<widget class="XLite_Module_GiftCertificates_View_AddStatesInfo" />
*}

<script type="text/javascript">
var gcMinAmount = {config.GiftCertificates.minAmount};
var gcMaxAmount = {config.GiftCertificates.maxAmount};
var bordersDir = '{gc.bordersDir}';
</script>

<p>Gift certificates are the perfect solution when you just can't  find the right gift or you're short of time. Gift Certificates make the perfect present for friends, family, and business associates.</p>
<p>If you already have a gift certificate you can <a href="{buildUrl(#check_gift_certificate#)}" class="gc-verify">verify it</a>.</p>

<widget class="XLite_Module_GiftCertificates_View_Form_GiftCertificate_Add" name="addgc" className="gift-certificate" />

  <h3>Certificate details</h3>

  <table cellpadding="0" class="form-table details">

    <tr>
      <td class="label"><label for="purchaser">From:</label></td>
      <td class="required">*</td>
      <td>
        <input type="text" id="purchaser" name="purchaser" value="{gc.purchaser}" />
      </td>
      <td><widget class="XLite_Validator_RequiredValidator" field="purchaser" /></td>
    </tr>

    <tr>
      <td class="label"><label for="recipient">To:</label></td>
      <td class="required">*</td>
      <td>
        <input type="text" id="recipient" name="recipient" value="{gc.recipient:r}" class="field-required" />
      </td>
      <td><widget class="XLite_Validator_RequiredValidator" field="recipient" /></td>
    </tr>

    <tr class="amount">
      <td class="label"><label for="amount">Amount</label></td>
      <td class="required">*</td>
      <td>
        <input type="text" id="amount" name="amount" value="{gc.amount}" class="field-required field-float field-range" />
        <span class="field-comment">{price_format(config.GiftCertificates.minAmount):h} - {price_format(config.GiftCertificates.maxAmount):h}</span>
      </td>
      <td><widget class="XLite_Validator_RangeValidator" field="amount" min="{config.GiftCertificates.minAmount}" max="{config.GiftCertificates.maxAmount}" /></td>
    </tr>

    <tr>
      <td class="label"><label for="message">Message:</label></td>
      <td>&nbsp;</td>
      <td colspan="2">
        <textarea id="message" name="message" cols="40" rows="4">{gc.message}</textarea>
      </td>
    </tr>

  </table>

  <h3>Delivery method</h3>
  <ul IF="config.GiftCertificates.enablePostGC" class="delivery">
    <li><input type="radio" id="send_via_e" name="send_via" value="E" checked="{gc.send_via=#E#}" /><label for="send_via_e">Send via e-mail</label></li>
    <li><input type="radio" id="send_via_p" name="send_via" value="P" checked="{gc.send_via=#P#}" /><label for="send_via_p">Send via Postal Mail</label></li>
  </ul>

  <hr class="tiny" />

  <table cellspacing="0" class="form-table delivery-email"{if:!gc.send_via=#E#} style="display: none;"{end:}>

    <tbody>

      <tr>
        <td class="descr" colspan="3">Enter the e-mail who you send a Gift Certificate to:</td>
      </tr>

      <tr>
        <td class="label"><label for="recipient_email">E-mail:</label></td>
        <td class="required">*</td>
        <td><input type="text" id="recipient_email" name="recipient_email" value="{gc.recipient_email:r}" class="field-email field-required" /></td>
      </tr>

      {* TODO - redesign needed
      <tr IF="gc.hasECards()" class="ecards">
        <td class="label">E-Card:</td>
        <td>&nbsp;</td>
        <td>

          <img IF="gc.ecard_id" src="{gc.eCard.thumbnail.url}" />

          <ul>
            <li><a href="{buildURL(#gift_certificate#,#select_ecard#,_ARRAY_(#gcid#^gcid))}">Select e-Card</a></li>
            <li IF="gc.ecard_id"><a href="{buildURL(#gift_certificate#,#delete_ecard#,_ARRAY_(#gcid#^gcid))}">Delete e-Card</a></li>
            <li IF="gc.ecard_id"><a href="{buildURL(#gift_certificate#,#preview_ecard#,_ARRAY_(#gcid#^gcid))}">Preview e-Card</a></li>
          </ul>

        </td>
      </tr>
      *}

    </tbody>

    {* TODO - redesign needed
    <tbody IF="gc.ecard_id">

      <tr>
        <td class="label"><label for="greetings">Greetings:</label></td>
        <td>&nbsp;</td>
        <td>
          <input type="text" id="greetings" name="greetings" value="{gc.greetings}" />
          <span class="field-comment">examples: 'Hi,' 'Dear'</span>
        </td>
      </tr>

      <tr>
        <td class="label"><label for="farewell">Farewell:</label></td>
        <td>&nbsp;</td>
        <td>
          <input type="text" id="farewell" name="farewell" value="{gc.farewell}" />
          <span class="field-comment">examples: 'From ', 'Lovely, '</span>
        </td>
      </tr>

      <tr IF="gc.ecard.needBorder">
        <td class="label"><label for="border">Border:</label></td>
        <td>&nbsp;</td>
        <td>
          <select name="border">
            <option FOREACH="gc.ecard.allBorders,_border" value="{_border}" selected="{gc.border=_border}">{_border}</option>
          </select>
          <img id="border_img" src="{gc.borderUrl}" alt="" />
        </td>
      </tr>

    </tbody>
    *}

  </table>

  {if:config.GiftCertificates.enablePostGC}
  <table cellspacing="0" class="form-table delivery-post"{if:!gc.send_via=#P#} style="display: none;"{end:}>

    <tr>
      <td class="descr" colspan="3">Enter the postal address who you're sending a Gift Certificate to.<br /><br /></td>
    </tr>

    <tr>
      <td class="label"><label for="recipient_firstname">First name:</label></td>
      <td class="required">*</td>
      <td><input type="text" id="recipient_firstname" name="recipient_firstname" value="{gc.recipient_firstname:r}" class="field-required" /></td>
    </tr>

    <tr>
      <td class="label"><label for="recipient_lastname">Last name:</label></td>
      <td class="required">*</td>
      <td><input type="text" id="recipient_lastname" name="recipient_lastname" value="{gc.recipient_lastname:r}" class="field-required" /></td>
    </tr>

    <tr>
      <td class="label"><label for="recipient_address">Address:</label></td>
      <td class="required">*</td>
      <td><input type="text" id="recipient_address" name="recipient_address" value="{gc.recipient_address:r}" class="field-required" /></td>
    </tr>

    <tr>
      <td class="label"><label for="recipient_city">City:</label></td>
      <td class="required">*</td>
      <td><input type="text" id="recipient_city" name="recipient_city" value="{gc.recipient_city:r}" class="field-required" /></td>
    </tr>

    <tr>
      <td class="label"><label for="recipient_state">State:</label></td>
      <td class="required">*</td>
      <td><widget class="XLite_View_StateSelect" field="recipient_state" onchange="javascript: changeState(this, 'recipient');" fieldId="recipient_state_select" /></td>
      <td><widget class="XLite_Validator_StateValidator" field="recipient_state" countryField="recipient_country" /></td>
    </tr>

    <tr id="recipient_custom_state_body">
      <td class="label"><label for="recipient_custom_state">Other state:</label></td>
      <td>&nbsp;</td>
      <td><input type="text" id="recipient_custom_state" name="recipient_custom_state" value="{gc.recipient_custom_state:r}" /></td>
    </tr>

    <tr>
      <td class="label"><label for="recipient_country">Country:</label></td>
      <td class="required">*</td>
      <td><widget class="XLite_View_CountrySelect" field="recipient_country" onchange="javascript: populateStates(this,'recipient');" fieldId="recipient_country_select" /></td>
      <td><widget class="XLite_Validator_RequiredValidator" field="recipient_country" /></td>
    </tr>

    <tr>
      <td class="label"><label for="recipient_zipcode">ZIP code:</label></td>
      <td class="required">*</td>
      <td><input type="text" id="recipient_zipcode" name="recipient_zipcode" value="{gc.recipient_zipcode:r}" class="field-required" /></td>
    </tr>

    <tr>
      <td class="label"><label for="recipient_phone">Phone:</label></td>
      <td>&nbsp;</td>
      <td><input type="text" id="recipient_phone" name="recipient_phone" value="{gc.recipient_phone:r}" /></td>
    </tr>

  </table>

  <widget template="js/select_states_begin_js.tpl" />
  <widget template="js/select_states_end_js.tpl" />
<script type="text/javascript">
<!--
$(document).ready(
  function() {
    var elm = document.getElementById('recipient_country_select');
    if (elm) {
      populateStates(elm, 'recipient', true);
    }

    elm = document.getElementById('recipient_state_select');
    if (elm) {
      changeState(elm, 'recipient');
    }
  }
);
-->
</script>
  {end:}

  <div class="buttons-row center">
    <widget IF="isGCAdded()" class="XLite_View_Button_Submit" label="Update" />
    <widget IF="!isGCAdded()" class="XLite_View_Button_Submit" label="Add certificate to cart" />
  </div>

<widget name="addgc" end />
