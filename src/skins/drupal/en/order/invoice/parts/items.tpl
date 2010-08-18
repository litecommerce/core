{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice items
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="invoice.base", weight="30")
 *}
<table cellspacing="0" class="invoice-items">

  <tr>
    <th class="name">Product</th>
    <th class="sku">SKU</th>
    <th class="price">Price</th>
    <th class="qty">Qty</th>
    <th class="total">Total</th>
  </tr>

  <tr FOREACH="order.getItems(),item">
    <td class="name">{displayViewListContent(#invoice.item.name#,_ARRAY_(#item#^item))}</td>
    <td class="sku">{item.getSku()}</td>
    <td class="price">{item.getPrice():p}</td>
    <td class="qty">{item.getAmount()}</td>
    <td class="total">{item.getTotal():p}</td>
  </tr>

  <tr FOREACH="getViewList(#invoice.items#),w">
    {w.display()}
  </tr>

</table>
