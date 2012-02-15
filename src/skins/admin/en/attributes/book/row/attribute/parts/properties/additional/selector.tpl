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

<tr IF="#Selector#=getAttributeTypeName()" class="additional-properties">
  <td colspan="3">

    <ul class="additional-properties">

      <li class="link">
        <widget class="\XLite\View\Button\Attribute\AddChoices" attribute="{getAttribute()}" />
      </li>

      <li IF="getAttributeChoices()" class="label">
        {t(#Default#)}:
      </li>

      <li IF="getAttributeChoices()" class="box">
        <select name="{getNamePostedData(#default#)}">
          <option
            FOREACH="getAttributeChoices(),choice"
            value="{choice.getId()}"
            selected="{getAttributeDefaultValue()=choice.getId()}">
            {choice.getTitle()}
          </option>
        </select>
      </li>

    </ul>

  </td>
</tr>
