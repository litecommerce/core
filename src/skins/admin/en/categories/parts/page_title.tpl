{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category page title
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="category.modify.list", weight="20")
 *}

<tr>
  <td valign="top">{t(#Category page title#)}</td>
  <td>&nbsp;</td>
  <td>
    <select name="show_title">
      <option value="1" selected="{category.show_title=#1#}">{t(#Use the category name#)}</option>
      <option value="0" selected="{category.show_title=#0#}">{t(#Hide#)}</option>
    </select>
</tr>
