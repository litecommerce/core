{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Users search form template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * TODO: RESTORE/REFACTOR Search form. ListChild (list="admin.profile.search", weight="10")
 *}

<script type="text/javascript">
<!--

function switchMembershipDisplaying(index)
{
  if (index == 1) {
    displ = 'none';
  } else {
    displ = '';
  }
  document.getElementById('membership_box').style.display = displ;
}

function resetForm()
{
  document.forms['searchform'].elements['action'].value = 'reset';
  document.forms['searchform'].submit();
}

function managedate(type, status) {

  if (type == 'date') {
    var fields = new Array('posted_data[startDate]','posted_data[endDate]');

    for (var i in fields)
      document.searchform.elements[fields[i]].disabled = status;

  } else if (type == 'date_type') {
    status = (document.forms['searchform'].elements['posted_data[date_type]'].selectedIndex == 0);

    if (!status)
      document.getElementById('date_period_box').style.display = '';
    else
      document.getElementById('date_period_box').style.display = 'none';
  }
}

-->
</script>

<p>Use this section to search and manage user accounts.</p>
<hr />

<form action="admin.php" method="post" name="searchform">

  <fieldset>
    <input type="hidden" name="target" value="profile_list" />
    <input type="hidden" name="action" value="search" />
  </fieldset>

  <table cellpadding="0" cellspacing="0">

  <tr>
    <td>

      <table cellpadding="1" cellspacing="5">

        <tr>

          <td style="width:20%;height:10px;" class="table-label">Search for pattern:</td>

          <td style="height:10px;" nowrap="nowrap">
            <input type="text" name="posted_data[pattern]" size="50" value="{getCondition(#pattern#)}" />
            &nbsp;
            <widget class="\XLite\View\Button\Submit" label="Search" />
          </td>

        </tr>

        <tr>

          <td></td>

          <td>(will search in emails and user names)</td>

        </tr>

      </table>

      <br />
      <widget template="common/advanced_search_options.tpl" title="More search options" mark="1" extra=' width="100%"' no_use_class="Y" />
      <br />

      <div style="display: none;" id="box1">

      <table cellpadding="1" cellspacing="5" width="100%">

        <tr>
          <td style="width:20%;height:10px;" class="table-label">Search for user type:</td>
          <td style="height:10px;">
            <select name="posted_data[user_type]" onchange="javascript: switchMembershipDisplaying(this.selectedIndex)">
              <option value=""{if:!getCondition(#user_type#)} selected="selected"{end:}>All</option>
              <option value="A"{if:getCondition(#user_type#)=#A#} selected="selected"{end:}>Administrator</option>
              <option value="C"{if:getCondition(#user_type#)=#C#} selected="selected"{end:}>Non-administrator</option>
            </select>
          </td>
        </tr>

        <tr id="membership_box">
          <td style="width:20%;height:10px;" class="table-label">Search for users with membership:</td>
          <td style="height:10px;">
            <widget class="\XLite\View\MembershipSelect" template="common/select_membership.tpl" field="posted_data[membership]" value="{getCondition(#membership#)}" allOption pendingOption />
          </td>
        </tr>

        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>

        <tr>
          <td style="width:20%;height:10px;" class="table-label">Country:</td>
          <td style="width:80%;height:10px;">
            <widget class="\XLite\View\CountrySelect" field="posted_data[country]" country="{getCondition(#country#)}" fieldId="country_select" />
          </td>
        </tr>

        <tr>
          <td style="width:20%;height:10px;" class="table-label">State:</td>
          <td style="width:80%;height:10px;">
            <widget class="\XLite\View\StateSelect" field="posted_data[state]" state="{getCondition(#state#)}" fieldId="state_select" isLinked=1 />
          </td>
        </tr>

        <tr>
          <td style="width:20%;height:10px;" class="table-label">Address pattern:</td>
          <td style="width:80%;height:10px;">
            <input type="text" maxlength="64" name="posted_data[address_pattern]" value="{getCondition(#address_pattern#)}" style="width:70%" />
          </td>
        </tr>

        <tr>
          <td></td>
          <td>(will search pattern in street, city and zip/postal code parts of addresses)</td>
        </tr>

        <tr>
          <td style="width:20%;height:10px;" class="table-label">Phone:</td>
          <td style="width:80%;height:10px;">
            <input type="text" maxlength="25" name="posted_data[phone]" value="{getCondition(#phone#)}" style="width:70%" />
          </td>
        </tr>

        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>

        <tr>
          <td style="height:10px;">Search for users that are:</td>
          <td style="height:10px;">
            <select name="posted_data[date_type]" onchange="javascript: managedate('date_type')">
              <option value=""{if:getCondition(#date_type#)=##} selected="selected"{end:}>[Please select one]</option>
              <option value="R"{if:getCondition(#date_type#)=#R#} selected="selected"{end:}>Registered...</option>
              <option value="L"{if:getCondition(#date_type#)=#L#} selected="selected"{end:}>Last logged in...</option>
            </select>
          </td>
        </tr>

        <tr id="date_period_box">
          <td class="table-label">During the period:</td>
          <td>

            <table cellpadding="2" cellspacing="2">

              <tr>
                <td style="width:5;"><input type="radio" id="date_period_M" name="posted_data[date_period]" value="M"{if:getCondition(#date_period#)=#M#|!getCondition(#date_period#)} checked="checked"{end:} onclick="javascript: managedate('date',true)" /></td>
                <td colspan="2" class="OptionLabel"><label for="date_period_M">This month</label></td>
              </tr>

              <tr>
                <td style="width:5;"><input type="radio" id="date_period_W" name="posted_data[date_period]" value="W"{if:getCondition(#date_period#)=#W#} checked="checked"{end:} onclick="javascript: managedate('date',true)" /></td>
                <td colspan="2" class="OptionLabel"><label for="date_period_W">This week</label></td>
              </tr>

              <tr>
                <td style="width:5;"><input type="radio" id="date_period_D" name="posted_data[date_period]" value="D"{if:getCondition(#date_period#)=#D#} checked="checked"{end:} onclick="javascript: managedate('date',true)" /></td>
                <td colspan="2" class="OptionLabel"><label for="date_period_D">Today</label></td>
              </tr>

              <tr>
                <td style="width:5;"><input type="radio" id="date_period_C" name="posted_data[date_period]" value="C"{if:getCondition(#date_period#)=#C#} checked="checked"{end:} onclick="javascript: managedate('date',false)" /></td>
                <td class="OptionLabel"><label for="date_period_C">From</label></td>
                <td><widget class="\XLite\View\DatePicker" field="posted_data[startDate]" value="{getCondition(#startDate#)}" /></td>
              </tr>

              <tr>
                <td></td>
                <td class="OptionLabel">Through</td>
                <td><widget class="\XLite\View\DatePicker" field="posted_data[endDate]" value="{getCondition(#endDate#)}" /></td>
              </tr>

            </table>

          </td>
        </tr>

        <tr>
          <td><widget class="\XLite\View\Button\Regular" label="Reset" jsCode="resetForm();" /></td>
          <td>
            <widget class="\XLite\View\Button\Submit" label="Search" />
          </td>
        </tr>

      </table>

      </div>

    </td>
  </tr>

</table>

<script type="text/javascript">
<!--
visibleBox('1');
managedate('date_type');
-->
</script>

{* TODO isAdvancedOptionSelected *}
{*
<script type="text/javascript" IF="isAdvancedOptionSelected()|mode=#search#&!getUsersCount()">
<!--
visibleBox('1');
-->
</script>
*}

<script type="text/javascript" IF="getCondition(#user_type#)=#A#">
<!--
switchMembershipDisplaying(1);
-->
</script>

<script type="text/javascript" IF="getCondition(#date_period#)=#C#">
<!--
managedate('date', false);
-->
</script>

<script type="text/javascript" IF="!getCondition(#date_period#)=#C#">
<!--
managedate('date', true);
-->
</script>

</form>
