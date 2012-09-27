{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category meta title
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="category.modify.list", weight="700")
 *}

<tr>
  <td>{t(#HTML title ('title' tag)#)}</td>
  <td class="star"></td>
  <td>
    <input type="text" name="{getNamePostedData(#metaTitle#)}" value="{category.getMetaTitle()}" size="50" />
  </td>
</tr>
