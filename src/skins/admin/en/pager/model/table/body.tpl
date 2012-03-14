{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Pager
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="left">
  <span class="title">{t(#Page#)}:</span>
  <a href="{buildURLByPageId(previousPageId)}" class="{getPrevClass()}" data-pageId="{getPreviousPageId()}"><span>{t(#Prev#)}</span></a>
  <div class="input"><input type="text" id="pager-input" name="pageId" value="{preprocessPageId(pageId)}" class="validate[maxSize[6],min[1],custom[integer]] wheel-ctrl no-wheel-mark not-significant" /></div>
  <a href="{buildURLByPageId(nextPageId)}" class="{getNextClass()}" data-pageId="{getNextPageId()}"><span>{t(#Next#)}</span></a>
  <div class="last"><span class="prefix">{t(#of#)}</span><a IF="!isLastPage()" href="{buildURLByPageId(lastPageId)}" data-pageId="{getLastPageId()}">{preprocessPageId(lastPageId)}</a><span IF="isLastPage()" class="page">{preprocessPageId(lastPageId)}</span></div>
</div>

<div class="right">
  <span>{t(#Items per page#)}:</span>
  <select name="itemsPerPage" class="page-length not-significant">
    <option FOREACH="getItemsPerPageRanges(),range" value="{range}" selected="{isRangeSelected(range)}">{range}</option>
  </select>
</div>
