{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Wishlists page template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<widget template="common/dialog.tpl" body="modules/CDev/WishList/search.tpl" head="Search wish lists" />

<span class="Text" IF="getRequestParamValue(#mode#)=#search#&count">
  Found {count} wishlist{if:count=#1#}{else:}s{end:}
  <br />
  <widget template="common/dialog.tpl" mode="search" body="modules/CDev/WishList/list.tpl" head="Search results" />
</span>

<span class="Text" IF="!count">No wish lists found</span>

