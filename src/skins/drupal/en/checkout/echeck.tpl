{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * E-check input form
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
    <td><label for="ch_routing_number">{t(#ABA routing number#)}:</label></td>
    <td class="marker">*</td>
    <td><input type="text" id="ch_routing_number" name="payment[routing_number]" value="" class="field-required" /></td>
  </tr>

  <tr>
    <td><label for="ch_acct_number">{t(#Bank Account Number#)}:</label></td>
    <td class="marker">*</td>
    <td><input type="text" id="ch_acct_number" name="payment[acct_number]" value="" class="field-required" /></td>
  </tr>

  <tr>
    <td><label for="ch_type">{t(#Type of Account#)}:</label></td>
    <td class="marker">*</td>
    <td>
      <select name="payment[type]" id="ch_type">
        <option value="CHECKING" selected="{isSelected(#CHECKINGS#,cart.details.ch_type)}">{t(#Checking#)}</option>
        <option value="SAVINGS" selected="{isSelected(#SAVINGS#,cart.details.ch_type)}">{t(#Savings#)}</option>
      </select>
    </td>
  </tr>

  <tr>
    <td><label for="ch_bank_name">{t(#Name of bank at which account is maintained#)}:</label></td>
    <td class="marker">*</td>
    <td><input type="text" id="ch_bank_name" name="payment[bank_name]" value="" class="field-required" /></td>
  </tr>

  <tr>
    <td><label for="ch_acct_name">{t(#Name under which the account is maintained at the bank#)}:</label></td>
    <td class="marker">*</td>
    <td><input type="text" id="ch_acct_name" name="payment[acct_name]" value="" class="field-required" /></td>
  </tr>

  <tr IF="processor.isDisplayNumber()">
    <td><label for="ch_number">{t(#Check number#)}:</label></td>
    <td class="marker">*</td>
    <td><input type="text" id="ch_number" name="payment[number]" value="" class="field-required" /></td>
  </tr>

</table>
