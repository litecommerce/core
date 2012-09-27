{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Clear bag button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="cart.buttons", weight="20")
 *}
<widget class="\XLite\View\Form\Cart\Clear" name="clearCart" />
  <div>
    <a href="{buildURL(#cart#,#clear#)}" onclick="javascript: return !jQuery(this).parents('form').eq(0).submit();" class="clear-bag">{t(#Clear bag#)}</a>
  </div>
<widget name="clearCart" end />
