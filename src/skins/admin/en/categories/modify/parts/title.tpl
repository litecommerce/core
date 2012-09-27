{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category title
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="category.modify.list", weight="200")
 *}

<tr>
  <td>{t(#Category page title#)}</td>
  <td class="star"></td>
  <td>
    <select name="{getNamePostedData(#show_title#)}">
      <option value="1" selected="{#1#=category.getShowTitle()}">{t(#Use the category name#)}</option>
      <option value="0" selected="{#0#=category.getShowTitle()}">{t(#Hide#)}</option>
    </select>
  </td>
</tr>
