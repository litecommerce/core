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

<tr IF="#Number#=getAttributeTypeName()|isNew()" class="additional-properties">
  <td colspan="3">

    <ul class="additional-properties number">

      <li class="label">{t(#Decimals#)}:</li>

      <li class="box">
        <select name="{getNamePostedData(#decimals#)}">
          <option FOREACH="getDecimalRange(),number" selected="{number=getAttributeDecimals()}">{number}</option>
        </select>
      </li>

      <li class="label">{t(#Unit#)}:</li>

      <li class="box">
        <input type="text" name="{getNamePostedData(#unit#)}" value="{getAttributeUnit():h}" />
      </li>

      <li class="note">({t(#suffix#)})</li>

      <li class="label">{t(#Default#)}:</li>

      <li class="box">
        <input type="text" name="{getNamePostedData(#default#)}" value="{getAttributeDefaultValue():h}" />
      </li>

    </ul>

  </td>
</tr>
