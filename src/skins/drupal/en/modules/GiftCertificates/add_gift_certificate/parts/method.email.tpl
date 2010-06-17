{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Gift certificate email delivery method
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="giftcert.methods")
 *}
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

