{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart Go to checkout button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="cart.panel.totals", weight="40")
 *}
<li class="button">
  <widget IF="cart.checkCart()" class="\XLite\View\Button\Link" label="Go to checkout" location="{buildURL(#checkout#)}" style="bright" />
  <widget IF="!cart.checkCart()" class="\XLite\View\Button\Link" label="Go to checkout" style="bright disabled add2cart-disabled" />
</li>
