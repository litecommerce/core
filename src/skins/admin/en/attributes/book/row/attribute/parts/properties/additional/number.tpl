{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Properties specific for the "Number" attributes
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 *
 * @ListChild (list="attributes.book.row.attribute.properties", weight="300")
 *}

<tr IF="#Number#=getAttributeTypeName()|isNew()" id="number_additional" class="additional-properties">
  <td>&nbsp;</td>
  <td colspan="10">
    <div class="additional-properties label">{t(#Decimals#)}:</div>
    <div class="additional-properties box">
      <select name="{getBoxName(#decimals#)}">
        <option FOREACH="getDecimalRange(),number" selected="{number=getAttributeDecimals()}">{number}</option>
      </select>
    </div>
    
    <div class="additional-properties label">{t(#Unit#)}:</div>
    <div class="additional-properties box">
      <input type="text" name="{getBoxName(#unit#)}" value="{getAttributeUnit():h}" />
    </div>
    <div class="additional-properties note">({t(#suffix#)})</div>

    <div class="additional-properties label">{t(#Default#)}:</div>
    <div class="additional-properties box">
      <input type="text" name="{getBoxName(#default#)}" value="{getAttributeDefaultValue():h}" />
    </div>
  </td>
</tr>
