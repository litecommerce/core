{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice items
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="invoice.base", weight="30")
 *}
<table cellspacing="1" width="100%">

  <tr bgcolor="grey">
    <list name="invoice.items.head" />
  </tr>
  <tr bgcolor="grey">
    <list name="invoice.items.subhead" />
  </tr>
  <tr class="separator">
    <td colspan="{getColumnsSpan()}"></td>
  </tr>

  {foreach:order.getItems(),index,item}
    <tr>
      <list name="invoice.item" item="{item}" />
    </tr>
    <tr>
      <list name="invoice.subitem" item="{item}" />
    </tr>
    <tr IF="itemArrayPointer=itemArraySize" bgcolor="#CCCCCC" class="separator">
      <td colspan="{getColumnsSpan()}"></td>
    </tr>
  {end:}

  <tr FOREACH="getViewList(#invoice.items#),w">
    {w.display()}
  </tr>

</table>
