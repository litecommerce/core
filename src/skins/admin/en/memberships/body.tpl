{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Memberships
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<p>{t(#Use this section to review the list of existing membership levels and add new ones#)}.</p>

<form IF="getMemberships()" action="admin.php" method="post" name="update_membership_form">
  <input type="hidden" name="target" value="memberships" />
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="language" value="{language}" />

  <table cellspacing="1" class="data-table">

    <tr>
      <th>{t(#Pos.#)}</th>
      <th class="extender">{t(#Membership name#)}</th>
      <th>{t(#Active#)}</th>
      <th><input type="checkbox" class="column-selector" /></th>
    </tr>

    <tr FOREACH="getMemberships(),m">
      <td>
        <input type="text" name="update_memberships[{m.getMembershipId()}][orderby]" value="{m.getOrderby()}" class="orderby" />
      </td>
      <td class="extender">
        <input type="text" name="update_memberships[{m.getMembershipId()}][name]" value="{m.getName()}" />
      </td>
      <td class="center">
        <widget
          class="\XLite\View\FormField\Input\Checkbox"
          fieldOnly="true"
          fieldName="update_memberships[{m.getMembershipId()}][active]"
          value="1"
          isChecked="{m.getActive()}" />
      </td>
      <td class="center">
        <widget
          class="\XLite\View\FormField\Input\Checkbox"
          fieldOnly="true"
          fieldName="deleted_memberships[]"
          value="{m.getMembershipId()}"
          isChecked="false" />
      </td>
    </tr>

  </table>

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" label="{t(#Update#)}" />
    <widget class="\XLite\View\Button\DeleteSelected" />
  </div>
</form>

<div IF="!getMemberships()">
  {t(#No memberships defined#)}.
</div>

<hr />

<form action="admin.php" method="post" name="add_membership_form">
  <input type="hidden" name="target" value="memberships" />
  <input type="hidden" name="action" value="add" />
  <input type="hidden" name="language" value="{language}" />

  <h2>{t(#Add new membership level#)}</h2>

  <ul class="form">

    <li>
      <label for="orderby">{t(#Position#)}</label>
      <input id="orderby" type="text" name="new_membership[orderby]" value="{getNextOrderBy()}" class="orderby" />
    </li>

    <li>
      <label for="membership">{t(#Membership name#)}</label>
      <input id="membership" type="text" name="new_membership[name]" value="" class="field-required" />
    </li>

  </ul>

  <widget class="\XLite\View\Button\Submit" label="{t(#Add#)}" />

</form>
