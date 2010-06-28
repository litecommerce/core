{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Memberships
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<p>Use this section to review the list of existing membership levels and add new ones.</p>

<div class="right-panel">
  <widget class="XLite_View_EditorLanguageSelector" />
</div>

<form IF="getMemberships()" action="admin.php" method="POST" name="update_membership_form">
  <input type="hidden" name="target" value="memberships">
  <input type="hidden" name="action" value="update">
  <input type="hidden" name="language" value="{language}">

  <table cellspacing="1" class="data-table">
		<tr>
			<th>Pos.</th>
			<th class="extender">Membership name</th>
      <th>Active</th>
      <th><input type="checkbox" class="column-selector" /></th>
		</tr>
		<tr FOREACH="getMemberships(),membership_id,membership">
			<td><input type="text" name="update_memberships[{membership_id}][orderby]" value="{membership.orderby}" class="orderby" /></td>
			<td class="extender"><input type="text" name="update_memberships[{membership_id}][membership]" value="{membership.name}" /></td>
      <td class="center"><input type="checkbox" name="update_memberships[{membership_id}][active]" value="1" checked="{membership.active}"/></td>
      <td class="center"><input type="checkbox" name="deleted_memberships[]" value="{membership_id}" /></td>
		</tr>

	</table>

  <div class="buttons">
    <widget class="XLite_View_Button_Submit" label="Update" />
    <widget class="XLite_View_Button_DeleteSelected" />
  </div>
</form>

<div IF="!getMemberships()">
  No memberships defined.
</div>

<hr />

<form action="admin.php" method="POST" name="add_membership_form">
  <input type="hidden" name="target" value="memberships">
  <input type="hidden" name="action" value="add">
  <input type="hidden" name="language" value="{language}">

  <h2>Add new membership level</h2>

  <ul class="form">

    <li>
      <label for="orderby">Position</label>
      <input id="orderby" type="text" name="new_membership[orderby]" value="{getNextOrderBy()}" class="orderby" />
    </li>

    <li>
      <label for="membership">Membership name</label>
    	<input id="membership" type="text" name="new_membership[membership]" value="" class="field-required" />
    </li>

  </ul>

  <widget class="XLite_View_Button_Submit" label="Add" />

</form>
