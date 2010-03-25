{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Wishlists items list template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<script>
function delete_warning() 
{
	if (confirm('You are about to delete a customer wish list.\n\nAre you sure you want to delete it?')) { 
		document.wishlists.submit(); 
		return true;
	}
	return false;
}
</script>

<widget class="XLite_View_Pager" data="{getWishLists()}" name="pager" />

<br />

<form name="wishlists" action="admin.php" method="GET">

  <input type="hidden" name="target" value="wishlists">
  <input type="hidden" name="mode" value="search">
  <input type="hidden" name="action" value="delete">

  <table border="0">

    <tr class="TableHead">
      <th>&nbsp;</th>
      <th>&nbsp;ID</th>
      <th nowrap>&nbsp;Creation date</th>
    	<th>E-mail</th>
      <th>Customer</th>
  	  <th></th>
    </tr>

    <tr FOREACH="namedWidgets.pager.pageData,wishlist">
    	<td><input type="checkbox" name="wishlistIds[]" value="{wishlist.wishlist_id:h}"></td>
    	<td><a href="admin.php?target=wishlist&wishlist_id={wishlist.wishlist_id:h}"><u>{wishlist.wishlist_id:h}</u></a></td>
    	<td align="center">{date_format(wishlist.date)}</td>	
    	<td><a href="admin.php?target=profile&profile_id={wishlist.profile.profile_id}"><u>{wishlist.profile.login:h}</u></a></td>
    	<td nowrap>{wishlist.profile.billing_lastname:h} {wishlist.profile.billing_firstname:h}</td>
    	<td nowrap><a href="admin.php?target=wishlist&wishlist_id={wishlist.wishlist_id:h}"><u>See details</u>&nbsp;&gt;&gt;</a></td>
    </tr>

  </table>

  <input type="button" value="Delete" onclick="javascript: delete_warning();" />

</form>
