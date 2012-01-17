{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Create box
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *}

<tr class="create-tpl" style="display: none;">
  <td FOREACH="getCreateColumns(),column" class="{getColumnClass(column,entity)}">
    {if:column.template}
      <widget template="{column.template}" idx="{idx}" entity="{getDumpEntity()}" column="{column}" />
    {else:}
      <widget class="{column.createClass}" idx="{idx}" entity="{getDumpEntity()}" column="{column}" itemsList="{getSelf()}" />
    {end:}
  </td>
</tr>
