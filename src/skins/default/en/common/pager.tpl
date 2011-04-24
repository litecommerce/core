{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Common pager
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<ul IF="isPagerVisible()" class="pager">

  <li class="{getBorderLinkClassName(#first#)}">
    <a href="{buildURLByPageId(getPageIdByNotation(#first#))}" class="{getLinkClassName(#first#)}"><img src="images/spacer.gif" alt="First" /></a>
  </li>
  <li class="{getBorderLinkClassName(#previous#)}">
    <a href="{buildURLByPageId(getPageIdByNotation(#previous#))}" class="{getLinkClassName(#previous#)}"><img src="images/spacer.gif" alt="Previous" /></a>
  </li>

  <li FOREACH="getPageURLs(),num,pageURL" class="{getPageClassName(num)}">
    <a href="{pageURL}" class="{getLinkClassName(num)}">{inc(num)}</a>
  </li>

  <li class="{getBorderLinkClassName(#next#)}">
    <a href="{buildURLByPageId(getPageIdByNotation(#next#))}" class="{getLinkClassName(#next#)}"><img src="images/spacer.gif" alt="Next" /></a>
  </li>
  <li class="{getBorderLinkClassName(#last#)}">
    <a href="{buildURLByPageId(getPageIdByNotation(#last#))}" class="{getLinkClassName(#last#)}"><img src="images/spacer.gif" alt="Last" /></a>
  </li>

</ul>
