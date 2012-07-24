{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Last page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="pager.admin.model.table.left", weight="200")
 *}

<div class="last">
  <span class="prefix">{t(#of#)}</span>
  <a IF="!isLastPage()" href="{buildURLByPageId(lastPageId)}" data-pageId="{getLastPageId()}">{preprocessPageId(lastPageId)}</a>
  <span IF="isLastPage()" class="page">{preprocessPageId(lastPageId)}</span>
</div>
