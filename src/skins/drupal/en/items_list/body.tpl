{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<div class="{getListCSSClasses()}">

  {displayCommentedData(getJSData())}

  <h2 IF="isHeadVisible()" class="items-list-title" >{getHead()}</h2>

  <div class="list-pager">{pager.display()}</div>

  <div IF="isHeaderVisible()" class="list-header">{displayViewListContent(#itemsList.header#)}</div>

  <widget template="{getPageBodyTemplate()}" />

  <div class="list-pager list-pager-bottom" IF="pager.isPagesListVisible()">{pager.display()}</div>

  <div IF="isFooterVisible()" class="list-footer">{displayViewListContent(#itemsList.footer#)}</div>

</div>
