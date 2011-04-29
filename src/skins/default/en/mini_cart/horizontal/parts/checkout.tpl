{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Horizontal minicart checkout button block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="minicart.horizontal.buttons", weight="10")
 *}
<div class="cart-checkout">
  {if:cart.checkCart()}
    <widget class="\XLite\View\Button\Link" label="Checkout" location="{buildURL(#checkout#)}" style="action" />
  {else:}
    <widget class="\XLite\View\Button\Link" label="Checkout" location="{buildURL(#checkout#)}" style="action" disabled="true" />
  {end:}
</div>
