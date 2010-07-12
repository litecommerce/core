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
<form action="admin.php" method="GET" name="search_user">
<table>
<tr>
    <td height="10" class="FormButton" nowrap>Substring</td>
    <td width="10" height="10"><font class="Star">*</font></td>
    <td><input type="text" name="substring" size="30" value="{substring:r}"></td>
</tr>
<tr>
    <td height="10" class="FormButton" nowrap>Membership</td>
    <td width="10" height="10">&nbsp;</td>
    <td>
        <widget class="\XLite\View\MembershipSelect" template="common/select_membership.tpl" field="membership" allOption pendingOption>
    </td>
</tr>
<tr>
    <td height="10" class="FormButton" nowrap>User type</td>
    <td>&nbsp;</td>
    <td>
        <select name="user_type">
            <option value="all" selected="{user_type=#all#}">All</option>
            <option FOREACH="auth.userTypes,user,type" value="{user}" selected="{isSelected(user_type,user)}">{type}</option>
        </select>
    </td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
    <td>
        <input type="hidden" name="target" value="{target}">
        <input type="hidden" name="order_id" value="{order_id}">
        <input type="hidden" name="mode" value="search_users">
        <input type="submit" name="search" value="Search">
        &nbsp;&nbsp;&nbsp;
        <input type="button" name="list" value="List all" onclick="document.search_user.substring.value=''; document.search_user.user_type.value='all'; document.search_user.membership.value='%'; document.search_user.submit();">
    </td>
</tr>
</table>
</form>
<br>
