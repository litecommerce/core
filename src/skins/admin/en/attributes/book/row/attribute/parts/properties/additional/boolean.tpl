{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Properties specific for the "Boolean" attributes
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 *
 * @ListChild (list="attributes.book.row.attribute.properties", weight="300")
 *}

<tr IF="#Boolean#=getAttributeTypeName()|isNew()" id="boolean_additional" class="additional-properties">
  <td>&nbsp;</td>
  <td colspan="10">
    <div class="additional-properties label">{t(#Default#)}:</div>
    <div class="additional-properties box">
      <select name="{getBoxName(#default#)}">
        <option value="{#1#}" selected="{getAttributeDefaultValue()}">{t(#True#)}</option>
        <option value="{#0#}" selected="{!getAttributeDefaultValue()}">{t(#False#)}</option>
      </select>
    </div>
  </td>
</tr>
