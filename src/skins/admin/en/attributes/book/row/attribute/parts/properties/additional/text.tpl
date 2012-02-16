{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Properties specific for the "Text" attributes
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 *
 * @ListChild (list="attributes.book.row.attribute.properties", weight="300")
 *}

<tr IF="#Text#=getAttributeTypeName()|isNew()" class="additional-properties">
  <td></td>
  <td colspan="2">
    <ul class="additional-properties">

      <li class="label">{t(#Default#)}:</li>

      <li class="box">
        <input type="text" name="{getNamePostedData(#default#)}" value="{getAttributeDefaultValue():h}" />
      </li>

    </ul>
  </td>
</tr>

