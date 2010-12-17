{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Gift certificatge details block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="giftcert.childs", weight="20")
 *}
<h3>Certificate details</h3>

<table cellpadding="0" class="form-table details">

  <tr>
    <td class="label"><label for="purchaser">From:</label></td>
    <td class="required">*</td>
    <td>
      <input type="text" id="purchaser" name="purchaser" value="{gc.purchaser}" />
    </td>
    <td><widget class="\XLite\Validator\RequiredValidator" field="purchaser" /></td>
  </tr>

  <tr>
    <td class="label"><label for="recipient">To:</label></td>
    <td class="required">*</td>
    <td>
      <input type="text" id="recipient" name="recipient" value="{gc.recipient:r}" class="field-required" />
    </td>
    <td><widget class="\XLite\Validator\RequiredValidator" field="recipient" /></td>
  </tr>

  <tr class="amount">
    <td class="label"><label for="amount">Amount</label></td>
    <td class="required">*</td>
    <td>
      <input type="text" id="amount" name="amount" value="{gc.amount}" class="field-required field-float field-range" />
      <span class="field-comment">{price_format(config.CDev.GiftCertificates.minAmount):h} - {price_format(config.CDev.GiftCertificates.maxAmount):h}</span>
    </td>
    <td><widget class="\XLite\Validator\RangeValidator" field="amount" min="{config.CDev.GiftCertificates.minAmount}" max="{config.CDev.GiftCertificates.maxAmount}" /></td>
  </tr>

  <tr>
    <td class="label"><label for="message">Message:</label></td>
    <td>&nbsp;</td>
    <td colspan="2">
      <textarea id="message" name="message" cols="40" rows="4">{gc.message}</textarea>
    </td>
  </tr>

</table>

