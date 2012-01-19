{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Properties specific for the "Selector" attributes
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 *
 * @ListChild (list="attributes.book.row.attribute.properties", weight="300")
 *}

<tr IF="#Selector#=getAttributeTypeName()|isNew()" id="selector_additional" class="additional-properties">
  <td>&nbsp;</td>
  <td colspan="10">
    <div class="additional-properties link">
      <a href="">{getSelectorChoicesLinkTitle()}</a>
    </div>

    <div IF="getAttributeChoices()" class="additional-properties label">{t(#Default#)}:</div>
    <div IF="getAttributeChoices()" class="additional-properties box">
      <select name="{getNamePostedData(#default#)}">
        <option FOREACH="getAttributeChoices(),choice" value="{choice.getId()}" selected="{getAttributeDefaultValue()=choice.getId()}">{choice.getTitle()}</option>
      </select>
    </div>
  </td>
</tr>
