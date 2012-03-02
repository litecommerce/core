{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Name item cell
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="invoice.item", weight="10")
 *}
<td class="name" colspan="{getItemDescriptionCount()}">
  <a IF="item.getURL()" href="{item.getURL()}">{item.getName()}</a>
  <span IF="!item.getURL()">{item.getName()}</span>
  <span IF="!item.product.isPersistent()" class="deleted-product-note">(deleted)</span>
  <div IF="isViewListVisible(#invoice.item.name#,_ARRAY_(#item#^item))" class="additional">
    <list name="invoice.item.name" item="{item}" />
  </div>
</td>
