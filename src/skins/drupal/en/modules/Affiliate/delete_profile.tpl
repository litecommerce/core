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
<table>
<tr IF="submode=#warning#">
<!-- Show delete profile confirmation dialog -->
<td>
<span class="Text">
Do you really want to delete your profile?
</span>
<p>
<a class="FormButton" href="cart.php?target=partner_profile&action=delete"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"><font class="FormButton"> Yes</font></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a class="FormButton" href="cart.php?target=partner_profile&mode=delete&submode=cancelled"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"><font class="FormButton"> No</font></a>
</p>
</td>
</tr>

<tr IF="submode=#confirmed#">
<!-- Show deleted profile message -->
<td>
Your profile was deleted successfully.
</td>
</tr>

<tr IF="submode=#cancelled#">
<!-- Show cancel profile delete message -->
<td>
Your profile has not been deleted.
</td>
</tr>
</table>
