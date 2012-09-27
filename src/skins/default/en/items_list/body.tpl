{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="{getListCSSClasses()}">
  {displayCommentedData(getJSData()):s}

  <h2 IF="isHeadVisible()" class="items-list-title">{getListHead()}</h2>
  <div IF="isPagerVisible()" class="list-pager">{pager.display():s}</div>
  <div IF="isHeaderVisible()" class="list-header"><list name="header" type="inherited" /></div>

  <widget template="{getPageBodyTemplate()}" />

  <div class="list-pager list-pager-bottom" IF="isPagerVisible()&pager.isPagesListVisible()">{pager.display():s}</div>
  <div IF="isFooterVisible()" class="list-footer"><list name="footer" type="inherited" /></div>

</div>
