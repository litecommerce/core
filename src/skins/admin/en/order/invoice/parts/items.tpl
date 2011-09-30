{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice items
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="invoice.base", weight="30")
 *}
<table cellspacing="0" class="items">

  <tr>
    {displayViewListContent(#invoice.items.head#)}
  </tr>
  <tr class="last-row">
    {displayViewListContent(#invoice.items.subhead#)}
  </tr>
  <tr class="separator">
    <td colspan="{getColumnsSpan()}"><img src="images/spacer.gif" alt="" /></td>
  </tr>

  {foreach:order.getItems(),index,item}
    <tr>
      {displayViewListContent(#invoice.item#,_ARRAY_(#item#^item))}
    </tr>
    <tr>
      {displayViewListContent(#invoice.subitem#,_ARRAY_(#item#^item))}
    </tr>
    <tr IF="!itemArrayPointer=itemArraySize" class="separator">
      <td colspan="{getColumnsSpan()}"><img src="images/spacer.gif" alt="" /></td>
    </tr>
  {end:}

  <tr FOREACH="getViewList(#invoice.items#),w">
    {w.display()}
  </tr>

</table>
