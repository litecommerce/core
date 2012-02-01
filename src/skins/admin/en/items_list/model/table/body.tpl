{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Common table-based model list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *}

<table class="list" cellspacing="0">

  <thead>
    <tr>
      <th FOREACH="getColumns(),column" class="{getHeadClass(column)}">
        <widget template="items_list//model/table/parts/head.cell.tpl" />
      </th>
    </tr>
  </thead>

  <tbody IF="isTopInlineCreation()" class="create top-create">
    <widget template="items_list/model/table/parts/create_box.tpl" />
  </tbody>

  <tbody class="lines">
    {foreach:getPageData(),idx,entity}
      <tr class="{getLineClass(idx,entity)}">
        <td FOREACH="getColumns(),column" class="{getColumnClass(column,entity)}">
          {if:column.template}
            <widget template="{column.template}" idx="{idx}" entity="{entity}" column="{column}" />
          {else:}
            <widget class="{column.class}" idx="{idx}" entity="{entity}" column="{column}" itemsList="{getSelf()}" />
          {end:}
        </td>
      </tr>
      {displayInheritedViewListContent(#row#,_ARRAY_(#idx#^idx,#entity#^entity))}
    {end:}
  </tbody>

  <tbody IF="isBottomInlineCreation()" class="create bottom-create">
    <widget template="items_list/model/table/parts/create_box.tpl" />
  </tbody>

</table>
