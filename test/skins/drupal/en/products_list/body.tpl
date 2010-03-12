{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="products-list">

  <div IF="isPagerVisible()" class="list-pager">
    <widget class="{getPagerClass()}" data="{getData()}" name="{getPagerName()}" />
  </div>

  <script type="text/javascript">
    productsList.URLParams = {getURLParams()};
    productsList.URLAJAXParams = {getURLAJAXParams()};
  </script>

  <div IF="isDisplayModeAdjustable()&isSortBySelectorVisible()" class="list-head">

    <div IF="isDisplayModeAdjustable()" class="display-modes">
      View as:
      <ul>
        <li FOREACH="displayModes,key,name" class="{getDisplayModeLinkClassName(key)}"><a href="javascript: productsList.changeDisplayMode('{key}');">{name}</a></li>
      </ul>
    </div>

    <div IF="isSortBySelectorVisible()" class="sort-box">

      <span>Sort by</span>
      <select class="sort-crit" onchange="javascript: productsList.changeSortByMode(this.value);">
        <option FOREACH="sortByModes,key,name" value="{key}" selected="{isSortByModeSelected(key)}">{name}</option>
      </select>

      <a href="javascript: productsList.changeSortOrder();" class="{getSortOrderLinkClassName()}">{if:isSortOrderAsc()}&darr;{else:}&uarr;{end:}</a>

    </div>

  </div>

  <widget template="{getPageBodyTemplate()}"  />

  <div IF="isPagerVisible()" class="list-pager-low">
    <widget name="pager" />
  </div>

</div>
