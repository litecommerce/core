{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Offline method common configuration page
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<table cellspacing="1" cellpadding="5" class="settings-table">

  <tr>
    <td class="setting-name">
    <label for="settings_name">{t(#Name#)}</label>
    </td>
    <td>
    <input type="text" id="settings_name" name="properties[name]" value="{paymentMethod.getName()}" class="field-required" />
    </td>
  </tr>

  <tr>
    <td class="setting-instruction">
    <label for="settings_instruction">{t(#Instruction#)}</label>
    </td>
    <td>
    <textarea id="settings_instruction" name="properties[instruction]">{paymentMethod.getInstruction()}</textarea>
    </td>
  </tr>

</table>
