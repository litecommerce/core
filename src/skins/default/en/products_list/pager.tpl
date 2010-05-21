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

<div class="list-pager" IF="isPagerVisible()">

<ul IF="isPagesListVisible()" class="pager">

  <li class="{pager.getBorderLinkClassName(#first#)}">
    <a href="{getActionURL(_ARRAY_(#pageId#^pager.getPageIdByNotation(#first#)))}" class="{pager.getLinkClassName(#first#)}"><img src="images/spacer.gif" alt="First" /></a>
  </li>
  <li class="{pager.getBorderLinkClassName(#previous#)}">
    <a href="{getActionURL(_ARRAY_(#pageId#^pager.getPageIdByNotation(#previous#)))}" class="{pager.getLinkClassName(#previous#)}"><img src="images/spacer.gif" alt="Previous" /></a>
  </li>

  <li FOREACH="pager.getPageUrls(),num,pageUrl" class="{pager.getPageClassName(num)}">
    <a href="{getActionURL(_ARRAY_(#pageId#^num))}" class="{pager.getLinkClassName(num)}">{inc(num)}</a>
  </li>

  <li class="{pager.getBorderLinkClassName(#next#)}">
    <a href="{getActionURL(_ARRAY_(#pageId#^pager.getPageIdByNotation(#next#)))}" class="{pager.getLinkClassName(#next#)}"><img src="images/spacer.gif" alt="Next" /></a>
  </li>
  <li class="{pager.getBorderLinkClassName(#last#)}">
    <a href="{getActionURL(_ARRAY_(#pageId#^pager.getPageIdByNotation(#last#)))}" class="{pager.getLinkClassName(#last#)}"><img src="images/spacer.gif" alt="Last" /></a>
  </li>

</ul>

<div IF="!onlyPages">
  Items:
  <span class="begin-record-number">{pager.getBeginRecordNumber()}</span>
  &ndash;
  <span class="end-record-number">{pager.getEndRecordNumber()}</span> of <span class="records-count">{pager.getItemsTotal()}</span><span IF="pager.isItemsPerPageSelectorVisible()">, <input type="text" value="{pager.getItemsPerPage()}" class="page-length" /> per page</span>
</div>

</div>
