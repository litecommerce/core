{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="product.search.conditions", weight="60")
 *}

<tr>
  <td class="table-label">{t(#Inventory status#)}</td>
  <td style="width:10px;height:10px;"></td>
  <td>
    <select name="inventory">
      <option value="all" selected='{getCondition(#inventory#)=#all#}'>{t(#All#)}</option>
      <option value="low" selected='{getCondition(#inventory#)=#low#}'>{t(#Low stock#)}</option>
      <option value="out" selected='{getCondition(#inventory#)=#out#}'>{t(#Out of stock#)}</option>
    </select>
  </td>
</tr>
