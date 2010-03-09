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
<ul class="pager">

  <li class="{getBorderLinkClassName(#first#)}"><a href="{getFirstPageUrl()}"><img src="images/spacer.gif" alt="" /></a></li>
  <li class="{getBorderLinkClassName(#previous#)}"><a href="{getPreviousPageUrl()}"><img src="images/spacer.gif" alt="" /></a></li>

  <li FOREACH="getPageUrls(),num,pageUrl" class="{getPageClassName(num)}"><a href="{pageUrl:h}">{inc(num)}</a></li>

  <li class="{getBorderLinkClassName(#next#)}"><a href="{getNextPageUrl()}"><img src="images/spacer.gif" alt="" /></a></li>
  <li class="{getBorderLinkClassName(#last#)}"><a href="{getLastPageUrl()}"><img src="images/spacer.gif" alt="" /></a></li>

</ul>

<div>
  Items: <span class="begin-record-number">{getBeginRecordNumber()}</span>&ndash;<span class="end-record-number">{getEndRecordNumber()}</span> of <span class="records-count">{getItemsTotal()}</span>,
  <span IF="isItemsPerPageSelectorVisible()">
    <input type="text" value="{getItemsPerPage()}" class="page-length" onchange="javascript: return productsList.changePageLength(this);" /> per page
  </span>
</div>


