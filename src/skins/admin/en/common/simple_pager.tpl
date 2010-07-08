{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Simple pager
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<form class="simple-pager" action="{getParam(#url#)}" method="get" name="simple_pager_form">
  Pages:
  <a IF="isPrevPage()" href="{getPrevURL()}" class="arrow previous"><img src="images/spacer.gif" alt="" /></a>
  <input type="text" name="page" value="{getParam(#page#)}" />
  <a IF="isNextPage()" href="{getNextURL()}" class="arrow next"><img src="images/spacer.gif" alt="" /></a>
  of
  <a href="{getLastURL()}" class="total">{getParam(#pages#)}</a>
</form>
