{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product list pager
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="productsList.pager", weight="10")
 *}
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
