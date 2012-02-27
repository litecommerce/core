{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attribute internal identifier
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 *
 * @ListChild (list="attributes.book.row.attribute.properties", weight="200")
 *}

<tr class="additional-properties-type">
  <td class="label">{t(#Type#)}:</td>

  <td class="input">
    <select name="{getNamePostedData(#class#)}" disabled="{!isNew()}">
      <option FOREACH="getAttributeTypes(),key,label" value="{key}" selected="{key=getAttributeTypeName()}">{t(label)}</option>
    </select>
  </td>

  <td class="note">
    <widget class="\XLite\View\Tooltip" text="Some text" />
  </td>
</tr>
