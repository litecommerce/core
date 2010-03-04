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
  <div class="list-pager">
    <widget class="XLite_View_Pager" data="{getList()}" name="pager" urlParams="{getURLParams()}" />

<script type="text/javascript">
var productsListConfig = {
  URL: '{pageURLPattern:r}',
  ajaxURL: '{pageURLPatternAJAX:r}',
  itemsCount: {pager.getItemsTotal()},
  pagerItemsCount: {pager.getPagesCount()},
  displayModes: {getDisplayModesForJS()},
  itemsPerPageRange: {pager.getItemsPerPageRange()},
  urlTranslationTable: {getURLTranslationTableForJS()},
};
</script>

    <div>
      Items: <span class="begin-record-number">{pager.getBeginRecordNumber()}</span>&ndash;<span class="end-record-number">{pager.getEndRecordNumber()}</span> of <span class="records-count">{pager.getItemsTotal()}</span>,
      <input type="text" value="{pager.getItemsPerPage()}" class="page-length" onchange="javascript: return productsList.changePageLength(this);" /> per page
    </div>

  </div>

  <div class="list-head">

    <div IF="isDisplayModeChangable()" class="display-modes">
      View as:
      <ul>
        <li FOREACH="getDisplayModes(),key,name" class="{getDisplayModeLinkClassName(key)}"><a href="{buildPageURL(##,##,##,key)}">{name}</a></li>
      </ul>
    </div>

    <div class="sort-box">

      <span>Sort by</span>
      <select class="sort-crit" onchange="javascript: productsList.changeSortCriterion(this);">
        <option FOREACH="getSortCriterions(),key,name" value="{key}" selected="{isSortCriterionSelected(key)}">{name}</option>
      </select>

      <a href="{getSortOrderInvLink()}" class="{getSortOrderLinkClassName()}">{if:isSortOrderAsc()}&darr;{else:}&uarr;{end:}</a>

    </div>

  </div>

  <widget class="XLite_View_ProductsListPage" data="{pager.getPageData()}" displayMode="{getDisplayMode()}" widgetArguments="{getInheritedWidgetArguments()}" />

  <div class="list-pager-low">
    <widget name="pager" />

    <div>
      Items: <span class="begin-record-number">{pager.getBeginRecordNumber()}</span>&ndash;<span class="end-record-number">{pager.getEndRecordNumber()}</span> of <span class="records-count">{pager.getItemsTotal()}</span>
    </div>
  </div>

  <widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm" />
</div>
