{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Search by category
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.19
 *
 * @listChild (list="products.search.conditions.advanced", weight="200")
 *}

<tr>
  <td class="option-name title-category">{t(#Category#)}:</td>
  <td><widget class="\XLite\View\CategorySelect" fieldName="categoryId" selectedCategoryIds="{_ARRAY_(getCondition(#categoryId#))}" allOption /></td>
</tr>
