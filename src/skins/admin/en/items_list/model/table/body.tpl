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
      <th FOREACH="getColumns(),column" class="{getHeadClass(column)}">{column.name}</th>
    </tr>
  </thead>

  <tbody>
    <tr FOREACH="getPageData(),idx,entity" class="{getLineClass(idx,entity)}">
      <td FOREACH="getColumns(),column" class="{getColumnClass(column,entity)}">
        {if:column.template}
          <widget template="{column.template}" idx="{idx}" entity="{entity}" column="{column}" />
        {else:}
          <widget class="{column.class}" idx="{idx}" entity="{entity}" column="{column}" />
        {end:}
      </td>
    </tr>
  </tbody>

</table>

