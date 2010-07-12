{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart bottom buttons panel
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.bottom.left", weight="10")
 *}
<div class="cart-buttons">
  <widget class="\XLite\View\Form\Cart\Clear" name="clear_form" className="plain" />
    <widget class="\XLite\View\Button\Submit" label="Clear cart" template="shopping_cart/clear_cart_button.tpl" />
  <widget name="clear_form" end >
  <widget class="\XLite\View\Button\Link" label="Continue shopping" location="{getContinueURL()}" IF="getContinueURL()" />
</div>
