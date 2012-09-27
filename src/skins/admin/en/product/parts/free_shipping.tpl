{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.modify.list", weight="700")
 *}

<tr>
  <td class="name-attribute">{t(#Shippable#)}</td>
  <td class="star">*</td>
  <td class="value-attribute">
    <select name="{getNamePostedData(#free_shipping#)}">
      <option value="0" selected="{isSelected(product,#free_shipping#,#0#)}">{t(#Yes#)}</option>
      <option value="1" selected="{isSelected(product,#free_shipping#,#1#)}">{t(#No#)}</option>
    </select>
  </td>
</tr>
