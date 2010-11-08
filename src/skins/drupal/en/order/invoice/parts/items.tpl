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
<table cellspacing="0" class="items">

  <tr>
    {displayViewListContent(#invoice.items.head#)}
  </tr>

  <tr FOREACH="order.getItems(),item">
    {displayViewListContent(#invoice.item#,_ARRAY_(#item#^item))}
  </tr>

  <tr FOREACH="getViewList(#invoice.items#),w">
    {w.display()}
  </tr>

</table>
