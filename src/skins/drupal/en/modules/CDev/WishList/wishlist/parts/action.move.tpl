{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Wishlist item move control
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="wishlist.item.actions", weight="20")
 *}
<widget class="\XLite\Module\CDev\WishList\View\Form\Item\MoveToCart" name="wl_item_move" item="{item}" />

  <div class="item-buttons">
    <widget class="\XLite\View\Button\Regular" style="aux-button add-to-cart" label="Move to cart" />
    <div class="move-quantity" style="display: none;">
      Qty: <input type="text" name="amount" value="{item.amount}" />
    </div>
  </div>

<widget name="wl_item_move" end />
