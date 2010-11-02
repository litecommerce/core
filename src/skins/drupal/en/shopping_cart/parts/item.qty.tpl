{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart item : quantity
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.item", weight="50")
 *}
<td class="item-qty">
  <widget class="\XLite\View\Form\Cart\Item\Update" item="{item}" name="updateItem{item.getItemId()}" className="update-quantity" />
    <div>
      <input type="text" name="amount" value="{item.getAmount()}" class="quantity field-integer field-positive wheel-ctrl watcher"/>
    </div>
  <widget name="updateItem{item.getItemId()}" end />
</td>
