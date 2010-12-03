{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Send wishlist to friend
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="wishlist.childs", weight="20")
 *}
<widget class="\XLite\Module\CDev\WishList\View\Form\Wishlist\Send" name="wl_send" wishlist="{getWishlist()}" className="wishlist-send" />

  <label for="wishlist_recipient">Send entire wish list by e-mail:</label>
  <input type="text" id="wishlist_recipient" name="wishlist_recipient" value="{wishlist_recipient}" />
  <widget class="\XLite\Validator\EmailValidator" field="wishlist_recipient" />
  <widget class="\XLite\View\Button\Submit" label="Send" />

<widget name="wl_send" end />
