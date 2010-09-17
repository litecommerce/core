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
<table cellspacing="0" class="form-table">

  <tr>
    <td><label for="cc_name">{t(#Cardholder name#)}:</label></td>
    <td class="marker">*</td>
    <td>
      <input type="text" name="payment[name]" id="cc_name" value="{cart.profile.billing_firstname} {cart.profile.billing_lastname}" />
    </td>
  </tr>

  <tr>
    <td><label for="cc_number">{t(#Credit card number#)}:</label></td>
    <td class="marker">*</td>
    <td>
      <input type="text" name="payment[number]" id='cc_number' value="" class="field-required" />
    </td>
  </tr>

  <tr id='start_date'>
    <td><label for="cc_start_date">{t(#Start date#)}:</label></td>
    <td class="marker"><span id="start_date_star">*</span></td>
    <td>
      <input type="text" name="payment[start_date]" value="" size="4" maxlength="4" id="cc_start_date" />
    </td>
  </tr>

  <tr>
    <td><label for="cc_date">{t(#Expiration date#)}:</label></td>
    <td class="marker">*</td>
    <td>
      <input type="text" name="payment[date]" value="" size="4" maxlength="4" class="field-required" id="cc_date" />
    </td>
  </tr>

  <tr id='issue_number'>
    <td><label for="cc_issue">{t(#Issue no#)}:</label></td>
    <td class="marker"><span id="issue_number_star">*</span></td>
    <td>
      <input type="text" id="cc_issue" name="payment[issue]" value="" size="4" maxlength="4" /><br />
    </td>
  </tr>

  <tr>
    <td><label for="cc_cvv2">{t(#CVV2#)}:</label></td>
    <td class="marker"><span id="cvv2_label">*</span></td>
    <td>
      <input type="text" id='cc_cvv2' name="payment[cvv2]" value="" />
    </td>
  </tr>

</table>
