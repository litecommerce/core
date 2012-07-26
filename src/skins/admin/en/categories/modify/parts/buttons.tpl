{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Buttons
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="category.modify.list", weight="1200")
 *}

<tr>
  <td colspan="3">
    <widget IF="category.getCategoryId()" class="\XLite\View\Button\Submit" label="Update" />
    <widget IF="!category.getCategoryId()" class="\XLite\View\Button\Submit" label="Create category" />
  </td>
</tr>
