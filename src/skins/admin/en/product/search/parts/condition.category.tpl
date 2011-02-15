{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="product.search.conditions", weight="30")
 *}

<tr>
  <td class="table-label" height="10">In category</td>
  <td width="10" height="10"><span class="error-message">*</span></td>
  <td height="10">
    <widget class="\XLite\View\CategorySelect" fieldName="categoryId" selectedCategoryId="{getCondition(#categoryId#):r}" allOption />
  </td>
</tr>
