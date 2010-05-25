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
<div class="products-list {getSessionCell()}">

  <widget name="{getPagerName()}" template="products_list/pager.tpl" />

  <div IF="isDisplayModeAdjustable()&isSortBySelectorVisible()" class="list-head">

    <div IF="isDisplayModeAdjustable()" class="display-modes">
      View as:
      <ul>
        <li FOREACH="displayModes,key,name" class="{getDisplayModeLinkClassName(key)}">
          <a href="{getActionUrl(_ARRAY_(#displayMode#^key))}" class="{key}">{name}</a>
        </li>
      </ul>
    </div>

    <div IF="isSortBySelectorVisible()" class="sort-box">

      <span>Sort by</span>
      <select class="sort-crit">
        <option FOREACH="sortByModes,key,name" value="{key}" selected="{isSortByModeSelected(key)}">{name}</option>
      </select>

      <a href="{getActionUrl(_ARRAY_(#sortOrder#^getSortOrderToChange()))}" class="sort-order">{if:isSortOrderAsc()}&darr;{else:}&uarr;{end:}</a>

    </div>

  </div>

  <widget template="{getPageBodyTemplate()}" />

  <widget name="{getPagerName()}" onlyPages />

</div>

<script type="text/javascript">
new ProductsList('{getSessionCell()}', {getURLParamsJS():h}, {getURLAJAXParamsJS():h});
</script>
