{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Common pager
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul IF="isPagerVisible()" class="pager">

  <li class="{getBorderLinkClassName(#first#)}">
    <a href="{buildUrlByPageId(getPageIdByNotation(#first#))}" class="{getLinkClassName(#first#)}"><img src="images/spacer.gif" alt="First" /></a>
  </li>
  <li class="{getBorderLinkClassName(#previous#)}">
    <a href="{buildUrlByPageId(getPageIdByNotation(#previous#))}" class="{getLinkClassName(#previous#)}"><img src="images/spacer.gif" alt="Previous" /></a>
  </li>

  <li FOREACH="getPageUrls(),num,pageUrl" class="{getPageClassName(num)}">
    <a href="{pageUrl}" class="{getLinkClassName(num)}">{inc(num)}</a>
  </li>

  <li class="{getBorderLinkClassName(#next#)}">
    <a href="{buildUrlByPageId(getPageIdByNotation(#next#))}" class="{getLinkClassName(#next#)}"><img src="images/spacer.gif" alt="Next" /></a>
  </li>
  <li class="{getBorderLinkClassName(#last#)}">
    <a href="{buildUrlByPageId(getPageIdByNotation(#last#))}" class="{getLinkClassName(#last#)}"><img src="images/spacer.gif" alt="Last" /></a>
  </li>

</ul>
