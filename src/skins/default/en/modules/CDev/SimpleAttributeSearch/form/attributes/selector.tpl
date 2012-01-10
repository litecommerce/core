{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Certain attribute type box
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *
 * @ListChild (list="itemsList.product.search.form.options.parts.attributes.parts", weight="200")
 *}

<td IF="attribute.checkType(#Selector#)">
  <select name="{getAttributeBoxName(attribute)}">
    <option value="" selected="{!getAttributeBoxValue(attribute)}"></option>
    <option FOREACH="attribute.getChoices(),choice" value="{choice.getId()}" selected="{choice.getId()=getAttributeBoxValue(attribute)}">{choice.getTitle()}</option>
  </select>
</td>
