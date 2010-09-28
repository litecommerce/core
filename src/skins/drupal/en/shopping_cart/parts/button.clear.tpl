{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Clear bag button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.buttons", weight="20")
 *}
<widget class="\XLite\View\Form\Cart\Clear" name="clearCart" />
  <div>
    <a href="{buildUrl(#cart#,#clear#)}" onclick="javascript: return !$(this).parents('form').eq(0).submit();" class="clear-bag">{t(#Clear bag#)}</a>
  </div>
<widget name="clearCart" end />
