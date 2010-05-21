{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Credit card form
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script type="text/javascript">
var checkEnabled = parseInt('{config.General.enable_credit_card_validation}');
if (isNaN(checkEnabled)) {
  checkEnabled = 0;   
}

var card_codes = {};
{foreach:cart.paymentMethod.cardTypes,key,card}
	card_codes.{card.code} = {card.cvv2};	
{end:}

$(document).ready(
  function() {
    showSoloOrSwitch();
  }
);
$('.checkout-details').submit(CheckoutSubmit);
</script>

<h2>Credit card information</h2>
<div class="details">

  <table cellspacing="0" class="form-table">

    <tr>
      <td><label for="cc_type">Credit card type:<label></td>
      <td class="marker">*</td>
      <td>
        <select id="cc_type" name="cc_info[cc_type]" onchange="javascript: return showSoloOrSwitch();">
          <option FOREACH="cart.paymentMethod.cardTypes,card" value="{card.code:r}" selected="{isSelected(card,#code#,cart.details.cc_type)}">{card.card_type:h}</option>
        </select>
      </td>
    </tr>

    <tr>
      <td><label for="cc_name">Cardholder's name:</label></td>
      <td class="marker">*</td>
      <td>
        <input type="text" name="cc_info[cc_name]" id="cc_name" value="{cart.profile.billing_firstname} {cart.profile.billing_lastname}" />
      </td>
    </tr>

    <tr>
      <td><label for="cc_number">Credit card number:</label></td>
      <td class="marker">*</td>
      <td>
        <input type="text" name="cc_info[cc_number]" id='cc_number' value="{cart.details.cc_number:r}" />
      </td>
    </tr>

    <tr id='start_date'>
      <td>Start date:</td>
      <td class="marker"><span id="start_date_star">*</span></td>
      <td>
        <widget class="XLite_View_Date" field="cc_info_cc_start_date_" hide_days="1" higherYear="{getCurrentYear()}" showMonthsNumbers="1" /><br />
        <input type="checkbox" name="no_start_date" id="start_date_box" onclick="javascript: showStar('start_date');">&nbsp;<label for="start_date_box">My card has no "Start date" information</label>
        <input type="hidden" name="cc_info[cc_start_date]" value="MMYY" />
      </td>
    </tr>

    <tr>
      <td>Expiration date:</td>
      <td class="marker">*</td>
      <td>
        <widget class="XLite_View_Date" field="cc_info_cc_date_" hide_days="1" lowerYear="{getCurrentYear()}" yearsRange="5" showMonthsNumbers="1" />
        <input type="hidden" name="cc_info[cc_date]" value="MMYY" />
      </td>
    </tr>

    <tr id='issue_number'>
      <td><label for="cc_issue">Issue no.:</label></td>
      <td class="marker"><span id="issue_number_star">*</span></td>
      <td>
        <input type="text" id="cc_issue" name="cc_info[cc_issue]" value="{cart.details.cc_issue:r}" size="4" maxlength="4" /><br />
        <input type="checkbox" name="no_issue_number" id="issue_number_box" onclick="javascript: showStar('issue_number');">&nbsp;<label for="issue_number_box">My card has no "Issue no." information</label>
      </td>
    </tr>

    <tr>
      <td><label for="cc_cvv2">CVV2 / CVC2 / CID:</label></td>
      <td class="marker"><span id="cvv2_label">*</span></td>
      <td>
        <input type="text" id='cc_cvv2' name="cc_info[cc_cvv2]" value="{cart.details.cc_cvv2:r}" />
      </td>
    </tr>

  </table>
</div>
