{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list sorting control
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.product.grid.customer.header")
 * @ListChild (list="itemsList.product.list.customer.header")
 * @ListChild (list="itemsList.product.table.customer.header")
 *}

<div IF="isSortBySelectorVisible()" class="sort-box">

  <label for="{getSortWidgetId()}">{t(#Sort by#)}</label>
  <select class="sort-crit" id="{getSortWidgetId(true)}">
    <option FOREACH="sortByModes,key,name" value="{key}" selected="{isSortByModeSelected(key)}">{t(name)}</option>
  </select>
</div>
