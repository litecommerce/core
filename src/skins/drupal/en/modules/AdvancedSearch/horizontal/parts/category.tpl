{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products search category block (horizontal)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="advsearch.horizontal.childs", weight="50")
 *}
<tr class="separator">
  <td class="row-title" rowspan="2">Category:</td>
  <td colspan="2">
    <widget template="modules/AdvancedSearch/select_category.tpl" class="\XLite\View\CategorySelect" fieldName="search[category]" allOption selectedCategoryId="{search.category}" />
  </td>
</tr>

<tr>
  <td class="substring-options" colspan="2">
    <input type="checkbox" id="search_subcategories" name="search[subcategories]" checked="{search.subcategories}" />
    <label for="search_subcategories">search in subcategories</label>
  </td>
</tr>
