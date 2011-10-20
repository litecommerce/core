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
 * @ListChild (list="product.search.conditions", weight="40")
 *}

<tr>
  <td class="table-label">{t(#In category#)}</td>
  <td style="width:10px;height:10px;"><span class="error-message">*</span></td>
  <td class="table-input">
    <widget class="\XLite\View\CategorySelect" fieldName="categoryId" selectedCategoryIds="{_ARRAY_(getCondition(#categoryId#))}" allOption />
  </td>
</tr>
