{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.search.conditions", weight="10")
 *}

<tr>
  <td class="table-label">{t(#Pattern search#)}</td>
  <td style="width:10px;height:10px;"></td>
  <td style="height:10px;"><input type="text" size="30" name="substring" value="{getCondition(#substring#):r}" /></td>
</tr>
