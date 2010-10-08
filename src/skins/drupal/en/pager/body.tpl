{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Pager
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<ul class="pager grid-list" IF="isPagesListVisible()">

  <li class="item previous-page {if:isDisabledNotation(#first#)}disabled{else:}active{end:}">

    <a IF="!isDisabledNotation(#first#)" href="{buildUrlByPageId(getPageIdByNotation(#previous#))}" class="{getPageIdByNotation(#previous#)}" title="Previous page">&nbsp;</a>
    <span IF="isDisabledNotation(#first#)" class="{getPageIdByNotation(#previous#)}">&nbsp;</span>
  </li>

  <li class="item first-page" IF="isFurthermostPage(#first#)">
    <a href="{buildUrlByPageId(getPageIdByNotation(#first#))}" class="{getPageIdByNotation(#first#)}">{inc(getPageIdByNotation(#first#))}</a>
  </li>

  <li class="more-pages" IF="isFurthermostPage(#first#)"><span>...</span></li>

  <li FOREACH="getPageUrls(),num,pageUrl" class="item {num} {if:isCurrentPage(num)}selected{else:}active{end:}">
    <a href="{pageUrl}" class="{num}" IF="!isCurrentPage(num)">{inc(num)}</a>
    <span class="{num}" IF="isCurrentPage(num)">{inc(num)}</span>
  </li>

  <li class="more-pages" IF="isFurthermostPage(#last#)"><span>...</span></li>

  <li class="item last-page" IF="isFurthermostPage(#last#)">
    <a href="{buildUrlByPageId(getPageIdByNotation(#last#))}" class="{getPageIdByNotation(#last#)}">{inc(getPageIdByNotation(#last#))}</a>
  </li>

  <li class="item next-page {if:isDisabledNotation(#last#)}disabled{else:}active{end:}">
    <a IF="!isDisabledNotation(#last#)" href="{buildUrlByPageId(getPageIdByNotation(#next#))}" class="{getPageIdByNotation(#next#)}" title="Next page">&nbsp;</a>
    <span IF="isDisabledNotation(#last#)" class="{getPageIdByNotation(#next#)}">&nbsp;</span>
  </li>

</ul>

{displayListPart(#itemsTotal#)}
