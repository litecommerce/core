{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Page selector
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="pager.admin.model.table.left", weight="100")
 *}

<div class="pages-nav">
  <span class="title">{t(#Page#)}:</span>
  <a href="{buildURLByPageId(previousPageId)}" class="{getPrevClass()}" data-pageId="{getPreviousPageId()}"><span>{t(#Prev#)}</span></a>
  <div class="input"><input type="text" id="pager-input" name="pageId" value="{preprocessPageId(pageId)}" class="validate[maxSize[6],min[1],custom[integer]] wheel-ctrl no-wheel-mark not-significant" /></div>
  <a href="{buildURLByPageId(nextPageId)}" class="{getNextClass()}" data-pageId="{getNextPageId()}"><span>{t(#Next#)}</span></a>
</div>
