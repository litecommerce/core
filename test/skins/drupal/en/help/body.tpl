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
<table border="0" cellpadding="2" cellspacing="0">
<tbody>
<tr>
	<td><a href="cart.php?target=recover_password" class="SidebarItems"><FONT class="CategoriesList">Recover password</FONT></a></td>
</tr>
<tr>
	<td><a href="cart.php?target=help&amp;mode=contactus" class="SidebarItems"><FONT class="CategoriesList">Contact us</FONT></a></td>
</tr>
<tr>
	<td><a href="cart.php?target=help&amp;mode=privacy_statement" class="SidebarItems"><FONT class="CategoriesList">Privacy statement</FONT></a></td>
</tr>
<tr>
	<td><a href="cart.php?target=help&amp;mode=terms_conditions" class="SidebarItems"><FONT class="CategoriesList">Terms &amp; Conditions</FONT></a></td>
</tr>
</tbody>
<tbody IF="!isEmpty(xlite.factory.XLite_Model_ExtraPage.pages)">
<widget template="help/pages_links.tpl">
</tbody>
</table>
