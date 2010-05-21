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
<script language="Javascript" type="text/javascript">
<!--
function WishList_Add2Cart()
{
	if (isValid())
	{
		document.add_to_cart.target.value = 'wishlist'; 
		document.add_to_cart.action.value = 'add'; 
		document.add_to_cart.submit();
	}
}
-->
</script>
<widget class="XLite_View_Button" label="Add to Wish List" href="{href}" img="modules/WishList/wish_list_icon_empty.gif" font="FormButton">
