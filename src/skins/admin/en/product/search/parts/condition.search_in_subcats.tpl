{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.search.conditions", weight="50")
 *}

<tr>
  <td class="table-label">{t(#Search in subcategories#)}</td>
  <td style="width:10px;height:10px;"></td>
  <td class="table-input table-input-checkbox">
    <input type="checkbox" name="searchInSubcats" checked="{getCondition(#searchInSubcats#)|!mode=#search#}" value="1" />
  </td>
</tr>
