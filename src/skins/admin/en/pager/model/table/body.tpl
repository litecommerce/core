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
  <div IF="!isFirstPage()" class="first"><span>&hellip;</span><a href="{buildURLByPageId(firtstPageId)}">{preprocessPageId(firstPageId)}</a></div>
  <a href="{buildURLByPageId(previousPageId)}" class="{getPrevClass()}"><span>{t(#Prev#)}</span></a>
  <div class="input"><input type="text" name="pageId" value="{preprocessPageId(pageId)}" class="page-length" /></div>
  <a href="{buildURLByPageId(nextPageId)}" class="{getNextClass()}"><span>{t(#Next#)}</span></a>
  <div IF="!isLastPage()" class="last"><span>&hellip;</span><a href="{buildURLByPageId(lastPageId)}">{preprocessPageId(lastPageId)}</a></div>
</div>

<div class="right">
  <span>{t(#Items per page#)}:</span>
  <select name="itemsPerPage" class="page-length">
    <option FOREACH="getItemsPerPageRanges(),range" value="{range}" selected="{isRangeSelected(range)}">{range}</option>
  </select>
</div>
