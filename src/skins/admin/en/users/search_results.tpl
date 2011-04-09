{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Users list template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<widget class="\XLite\View\PagerOrig" data="{getUsers()}" name="searchResults" itemsPerPage="{config.General.users_per_page}" />

<script type="text/javascript">
<!--

function deleteProfile()
{
	if (confirm('Are you sure you want to delete the selected user?')) { 
		document.forms['user_profile'].elements['action'].value = 'delete'; 
		document.forms['user_profile'].submit();
	}
}

function searchOrders()
{
  document.forms['user_profile'].elements['target'].value = 'users';
  document.forms['user_profile'].elements['action'].value = 'orders';
  document.forms['user_profile'].submit();
}

// -->
</script>

<form action="admin.php" method="post" name="user_profile">
  <fieldset>
    <input type="hidden" name="target" value="profile" />
    <input type="hidden" name="action" value="" />
    <input type="hidden" name="backURL" value="{url:r}" />
  </fieldset>

  <table width="100%" class="data-table">

    <tr>
      <th style="width:10px;">&nbsp;</td>
      <th class="table-label" align="left">{t(#Login/E-mail#)}</th>
      <th class="table-label" align="left">{t(#Username#)}</th>
      <th class="table-label" align="left">{t(#Access level#)}</th>
      <th class="table-label" align="left">{t(#Orders count#)}</th>
      <th class="table-label" align="left" width="110">{t(#Created#)}</th>
      <th class="table-label" align="left" width="110">{t(#Last login#)}</th>
    </tr>

    <tr FOREACH="namedWidgets.searchResults.pageData,id,user" class="{getRowClass(id,##,#highlight#)}">
      <td align="center" width="10"><input type="radio" name="profile_id" value="{user.profile_id}" checked="{isSelected(id,#0#)}" /></td>
      <td class="table-label"><a href="{buildURL(#profile#,##,_ARRAY_(#profile_id#^user.profile_id))}">{user.login:h}</a>{if:!user.status=#E#} (disabled account){end:}</td>
      <td class="table-label"><a href="{buildURL(#address_book#,##,_ARRAY_(#profile_id#^user.profile_id))}">{if:user.billing_address.firstname&user.billing_address.lastname}{user.billing_address.firstname:h}&nbsp;{user.billing_address.lastname:h}{else:}n/a{end:}</a></td>
      <td class="table-label" align="left">
      {if:user.access_level=0}
        Customer
        {if:user.membership}
        <br /><b>membership:</b> {user.membership.getName()}
        {end:}
        {if:user.pending_membership}
        <br /><b>requested for membership:</b> {user.pending_membership.getName()}
        {end:}
      {else:}
        Administrator
      {end:}
      </td>
      <td class="table-label" align="left">{if:user.orders_count}<a href="{buildURL(#order_list#,##,_ARRAY_(#mode#^#search#,#login#^user.login))}">{user.orders_count}</a>{else:}n/a{end:}</td>
      <td class="table-label" align="left">{if:user.added}{formatTime(user.added):h}{else:}Unknown{end:}</td>
      <td class="table-label" align="left">{if:user.last_login}{formatTime(user.last_login):h}{else:}Never{end:}</td>
    </tr>

  </table>

  <br />

  <p align="left">
    
    <widget class="\XLite\View\Button\DeleteUser" name="Delete" label="Delete selected profile" jsCode="deleteProfile();" />

  </p>

</form>

{displayViewListContent(#admin.users.results.bottom#)}


