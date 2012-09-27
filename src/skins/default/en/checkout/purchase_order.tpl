{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Purchase order input form
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<table cellspacing="0" class="form-data">

  <tr>
    <td><label for="payment_number">{t(#Purchase order number#)}:</label></td>
    <td class="marker">*</td>
    <td><input type="text" id="payment_number" name="payment[number]" value="" class="field-required" autocomplete="off" /></td>
  </tr>

  <tr>
    <td><label for="payment_company">{t(#Company name#)}:</label></td>
    <td class="marker">*</td>
    <td><input type="text" id="payment_company" name="payment[company]" value="" class="field-required" autocomplete="off" /></td>
  </tr>

  <tr>
    <td><label for="payment_purchaser">{t(#Name of purchaser#)}:</label></td>
    <td class="marker">*</td>
    <td><input type="text" id="payment_purchaser" name="payment[purchaser]" value="" class="field-required" autocomplete="off" /></td>
  </tr>

  <tr>
    <td><label for="payment_position">{t(#Position#)}:</label></td>
    <td class="marker">*</td>
    <td><input type="text" id="payment_position" name="payment[position]" value="" class="field-required" autocomplete="off" /></td>
  </tr>

</table>
