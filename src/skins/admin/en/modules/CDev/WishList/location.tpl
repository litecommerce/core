{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<span IF="target=#wishlist#">&nbsp;::&nbsp;<a href="admin.php?target=wishlists" class="NavigationPath">Wish Lists</a></span>
<span IF="target=#wishlists#">&nbsp;::&nbsp;<a href="admin.php?target=wishlists" class="NavigationPath">Wish Lists</a></span>
<span IF="target=#wishlist#">&nbsp;::&nbsp;<a href="admin.php?target=wishlist&wishlist_id={wishlist_id}" class="NavigationPath">Wish List #{wishlist_id}</a></span>
