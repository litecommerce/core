{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * e-check form
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="details">

  <table cellspacing="0" class="form-table">

    <tr>
      <td><label for="ch_routing_number">ABA routing number:</label></td>
      <td class="marker">*</td>
      <td><input type="text" id="ch_routing_number" name="ch_info[ch_routing_number]" value="{cart.details.ch_routing_number:r}" class="field-required" /></td>
    </tr>

    <tr>
      <td><label for="ch_acct_number">Bank Account Number:</label></td>
      <td class="marker">*</td>
      <td><input type="text" id="ch_acct_number" name="ch_info[ch_acct_number]" value="{cart.details.ch_acct_number:r}" class="field-required" /></td>
    </tr>

    <tr>
      <td><label for="ch_type">Type of Account:</label></td>
      <td class="marker">*</td>
      <td>
        <select name="ch_info[ch_type]" id="ch_type">
          <option value="CHECKING" selected="{isSelected(#CHECKINGS#,cart.details.ch_type)}">Checking</option>
          <option value="SAVINGS" selected="{isSelected(#SAVINGS#,cart.details.ch_type)}">Savings</option>
        </select>
      </td>
    </tr>

    <tr>
      <td><label for="ch_bank_name">Name of bank at which account is maintained:</label></td>
      <td class="marker">*</td>
      <td><input type="text" id="ch_bank_name" name="ch_info[ch_bank_name]" value="{cart.details.ch_bank_name:r}" class="field-required" /></td>
    </tr>

    <tr>
      <td><label for="ch_acct_name">Name under which the account is maintained at the bank:</label></td>
      <td class="marker">*</td>
      <td><input type="text" id="ch_acct_name" name="ch_info[ch_acct_name]" value="{cart.details.ch_acct_name:r}" class="field-required" /></td>
    </tr>

    <tr IF="displayNumber">
      <td><label for="ch_number">Check number:</label></td>
      <td class="marker">*</td>
      <td><input type="text" id="ch_number" name="ch_info[ch_number]" value="{cart.details.ch_number:r}" class="field-required" /></td>
    </tr>

  </table>

</div>
