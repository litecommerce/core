{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="product.modify.list", weight="800")
 *}

<tr>
  <td class="name-attribute">{t(#Available for sale#)}</td>
  <td class="star">&nbsp;*&nbsp;</td>
  <td class="value-attribute">
    <select name="{getNamePostedData(#enabled#)}">
      <option value="1" selected="{isSelected(product,#enabled#,#1#)}">Yes</option>
      <option value="0" selected="{isSelected(product,#enabled#,#0#)}">No</option>
    </select>
  </td>
</tr>
