{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart item update control
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.item.actions", weight="10")
 *}
<widget class="\XLite\View\Form\Cart\Item\Update" name="item" item="{item}" cartId="{cart_id}" />
  <div class="item-sums">
    <span class="item-price">{price_format(item,#price#):h}</span>
    <span class="sums-multiply">x</span>
    <span class="item-quantity"><input type="text" name="amount" value="{item.amount}" class="wheel-ctrl field-integer field-positive field-non-zero" /></span>
    <widget class="\XLite\View\Button\Image" style="update-icon update-icon-disabled" label="Update" disabled />
    <span class="sums-equals">=</span>
    <span class="item-subtotal">{price_format(item,#total#):h}</span>
  </div>
<widget name="item" end />

