{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Common table-based model list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<table class="list" cellspacing="0">

  <thead IF="isTableHeaderVisible()">
    <tr>
      <th FOREACH="getColumns(),column" class="{getHeadClass(column)}">
        <widget template="items_list/model/table/parts/head.cell.tpl" />
      </th>
    </tr>
  </thead>

  <tbody IF="isHeadSearchVisible()" class="head-search">
    <widget template="items_list/model/table/parts/head_search.tpl" />
  </tbody>

  <tbody IF="isTopInlineCreation()" class="create top-create">
    <widget template="items_list/model/table/parts/create_box.tpl" />
  </tbody>

  <tbody class="lines">
    {foreach:getPageData(),idx,entity}
      <tr class="{getLineClass(idx,entity)}">
        <td FOREACH="getColumns(),column" class="{getColumnClass(column,entity)}">
          <widget IF="isTemplateColumnVisible(column,entity)" template="{column.template}" idx="{idx}" entity="{entity}" column="{column}" />
          <widget IF="isClassColumnVisible(column,entity)" class="{column.class}" idx="{idx}" entity="{entity}" column="{column}" itemsList="{getSelf()}" fieldName="{column.code}" fieldParams="{column.params}" />
          <list type="inherited" name="{getCellListNamePart(#cell#,column)}" column="{column}" entity="{entity}" />
        </td>
      </tr>
      <list type="inherited" name="row" idx="{idx}" entity="{entity}" />
    {end:}
  </tbody>

  <tbody class="no-items" {if:hasResults()}style="display: none;"{end:}>
    <tr>
      <td colspan="{getColumnsCount()}"><widget template="{getEmptyListTemplate()}" /></td>
    </tr>
  </tbody>

  <tbody IF="isBottomInlineCreation()" class="create bottom-create">
    <widget template="items_list/model/table/parts/create_box.tpl" />
  </tbody>

</table>
