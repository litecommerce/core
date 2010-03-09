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
<div class="products-list" id="{getContainerId()}">
  <div IF="isPagerVisible()" class="list-pager">

    <widget class="XLite_View_Pager" data="{getList()}" name="pager" urlParams="{getURLParams()}" />

    <div>
      Items: <span class="begin-record-number">{pager.getBeginRecordNumber()}</span>&ndash;<span class="end-record-number">{pager.getEndRecordNumber()}</span> of <span class="records-count">{pager.getItemsTotal()}</span><span IF="isItemsPerPageSelectorVisible()">, <input type="text" value="{pager.getItemsPerPage()}" class="page-length" /> per page</span>
    </div>

  </div>

  <div IF="isDisplayModeAdjustable()&isSortCriterionVisible()" class="list-head">

    <div IF="isDisplayModeAdjustable()" class="display-modes">
      View as:
      <ul>
        <li FOREACH="getDisplayModes(),key,name" class="{getDisplayModeLinkClassName(key)}"><a href="{buildPageURL(##,##,##,key)}">{name}</a></li>
      </ul>
    </div>

    <div IF="isSortCriterionVisible()" class="sort-box">

      <span>Sort by</span>
      <select class="sort-crit">
        <option FOREACH="getSortCriterions(),key,name" value="{key}" selected="{isSortCriterionSelected(key)}">{name}</option>
      </select>

      <a href="{getSortOrderInvLink()}" class="{getSortOrderLinkClassName()}">{if:isSortOrderAsc()}&darr;{else:}&uarr;{end:}</a>

    </div>

  </div>

<script type="text/javascript">
$(document).ready(
  function() {
    new productsList(
      '{getCellName()}',
      {
        URL: '{pageURLPattern:r}',
        ajaxURL: '{pageURLPatternAJAX:r}',
        itemsCount: {getItemsCount()},
        pagerItemsCount: {getPagesCount()},
        displayModes: {getDisplayModesForJS()},
        itemsPerPageRange: {getItemsPerPageRange()},
        urlTranslationTable: {getURLTranslationTableForJS()},
      }
    );
  }
);
</script>

  <widget class="{getPageWidgetClass()}" data="{getPageList()}" displayMode="{getDisplayMode()}" widgetArguments="{getInheritedWidgetArguments()}" />

  <div IF="isPagerVisible()" class="list-pager-low">
    <widget name="pager" />

    <div>
      Items: <span class="begin-record-number">{pager.getBeginRecordNumber()}</span>&ndash;<span class="end-record-number">{pager.getEndRecordNumber()}</span> of <span class="records-count">{pager.getItemsTotal()}</span>
    </div>
  </div>

  <widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm" />
</div>
