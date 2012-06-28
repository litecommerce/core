{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category availability
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.21
 *
 * @ListChild (list="category.modify.list", weight="600")
 *}

<tr>
  <td>{t(#Availability#)}</td>
  <td class="star">*</td>
  <td>
    <select name="{getNamePostedData(#enabled#)}">
      <option value="1" selected="{category.getEnabled()=#1#}">{t(#Enabled#)}</option>
      <option value="0" selected="{category.getEnabled()=#0#}">{t(#Disabled#)}</option>
    </select>
  </td>
</tr>
