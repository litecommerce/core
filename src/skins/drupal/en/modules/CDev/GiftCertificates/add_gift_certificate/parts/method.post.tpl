{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Gift certificate postal mail delivery method
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="giftcert.methods")
 *}
{if:config.CDev.GiftCertificates.enablePostGC}
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
    <td><widget class="\XLite\View\StateSelect" field="recipient_state" state="{gc.recipient_state}" isLinked=1 /></td>
    <td><widget class="\XLite\Validator\StateValidator" field="recipient_state" countryField="recipient_country" /></td>
  </tr>

  <tr>
    <td class="label"><label for="recipient_custom_state">Other state:</label></td>
    <td>&nbsp;</td>
    <td><input type="text" name="recipient_custom_state" value="{gc.recipient_custom_state:r}" /></td>
  </tr>

  <tr>
    <td class="label"><label for="recipient_country">Country:</label></td>
    <td class="required">*</td>
    <td><widget class="\XLite\View\CountrySelect" field="recipient_country" country="{gc.recipient_country}" /></td>
    <td><widget class="\XLite\Validator\RequiredValidator" field="recipient_country" /></td>
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
{end:}
