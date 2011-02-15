{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Users search form template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<script type="text/javascript" language="JavaScript 1.2">
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

<p align="justify">
  Use this section to search and manage user accounts.
  <hr />
</p>

<form action="admin.php" method="post" name="searchform">

  <input type="hidden" name="target" value="users" />
  <input type="hidden" name="action" value="search" />

  <table cellpadding="0" cellspacing="0" width="100%">

  <tr>
    <td>

      <table cellpadding="1" cellspacing="5" width="100%">

        <tr>
          <td style="width:20%;height:10px;" class="table-label">Search for pattern:</td>
          <td style="height:10px;">
            <input type="text" name="posted_data[pattern]" size="30" style="width:70%" value="{getSearchParams(#pattern#)}" />
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
            <select name="posted_data[user_type]" onChange="javascript: switchMembershipDisplaying(this.selectedIndex)">
              <option value=""{if:!getSearchParams(#user_type#)} selected="selected"{end:}>All</option>
              <option value="A"{if:getSearchParams(#user_type#)=#A#} selected="selected"{end:}>Administrator</option>
              <option value="C"{if:getSearchParams(#user_type#)=#C#} selected="selected"{end:}>Non-administrator</option>
            </select>
          </td>
        </tr>

        <tr id="membership_box">
          <td style="width:20%;height:10px;" class="table-label">Search for users with membership:</td>
          <td style="height:10px;">
            <widget class="\XLite\View\MembershipSelect" template="common/select_membership.tpl" field="posted_data[membership]" value="{getSearchParams(#membership#)}" allOption pendingOption />
          </td>
        </tr>

        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>

        <tr>
          <td style="width:20%;height:10px;" class="table-label">Country:</td>
          <td style="width:80%;height:10px;">
            <widget class="\XLite\View\CountrySelect" field="posted_data[country]" country="{getSearchParams(#country#)}" fieldId="country_select" />
          </td>
        </tr>

        <tr>
          <td style="width:20%;height:10px;" class="table-label">State:</td>
          <td style="width:80%;height:10px;">
            <widget class="\XLite\View\StateSelect" field="posted_data[state]" state="{getSearchParams(#state#)}" fieldId="state_select" isLinked=1 />
          </td>
        </tr>

        <tr>
          <td style="width:20%;height:10px;" class="table-label">Address pattern:</td>
          <td style="width:80%;height:10px;">
            <input type="text" maxlength="64" name="posted_data[address_pattern]" value="{getSearchParams(#address_pattern#)}" style="width:70%" />
          </td>
        </tr>

        <tr>
          <td></td>
          <td>(will search pattern in street, city and zip/postal code parts of addresses)</td>
        </tr>

        <tr>
          <td style="width:20%;height:10px;" class="table-label">Phone:</td>
          <td style="width:80%;height:10px;">
            <input type="text" maxlength="25" name="posted_data[phone]" value="{getSearchParams(#phone#)}" style="width:70%" />
          </td>
        </tr>

        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>

        <tr>
          <td style="height:10px;">Search for users that are:</td>
          <td style="height:10px;">
            <select name="posted_data[date_type]" onChange="javascript: managedate('date_type')">
              <option value=""{if:getSearchParams(#date_type#)=##} selected="selected"{end:}>[Please select one]</option>
              <option value="R"{if:getSearchParams(#date_type#)=#R#} selected="selected"{end:}>Registered...</option>
              <option value="L"{if:getSearchParams(#date_type#)=#L#} selected="selected"{end:}>Last logged in...</option>
            </select>
          </td>
        </tr>

        <tr id="date_period_box">
          <td class="table-label">During the period:</td>
          <td>

            <table cellpadding="2" cellspacing="2">

              <tr>
                <td style="width:5;"><input type="radio" id="date_period_M" name="posted_data[date_period]" value="M"{if:getSearchParams(#date_period#)=#M#|!getSearchParams(#date_period#)} checked="checked"{end:} onclick="javascript: managedate('date',true)" /></td>
                <td colspan="2" class="OptionLabel"><label for="date_period_M">This month</label></td>
              </tr>

              <tr>
                <td style="width:5;"><input type="radio" id="date_period_W" name="posted_data[date_period]" value="W"{if:getSearchParams(#date_period#)=#W#} checked="checked"{end:} onclick="javascript: managedate('date',true)" /></td>
                <td colspan="2" class="OptionLabel"><label for="date_period_W">This week</label></td>
              </tr>

              <tr>
                <td style="width:5;"><input type="radio" id="date_period_D" name="posted_data[date_period]" value="D"{if:getSearchParams(#date_period#)=#D#} checked="checked"{end:} onclick="javascript: managedate('date',true)" /></td>
                <td colspan="2" class="OptionLabel"><label for="date_period_D">Today</label></td>
              </tr>

              <tr>
                <td style="width:5;"><input type="radio" id="date_period_C" name="posted_data[date_period]" value="C"{if:getSearchParams(#date_period#)=#C#} checked="checked"{end:} onclick="javascript: managedate('date',false)" /></td>
                <td class="OptionLabel"><label for="date_period_C">From</label></td>
                <td><widget class="\XLite\View\DatePicker" field="posted_data[startDate]" value="{getSearchParams(#startDate#)}" /></td>
              </tr>

              <tr>
                <td></td>
                <td class="OptionLabel">Through</td>
                <td><widget class="\XLite\View\DatePicker" field="posted_data[endDate]" value="{getSearchParams(#endDate#)}" /></td>
              </tr>

            </table>

          </td>
        </tr>

        <tr>
          <td><widget class="\XLite\View\Button\Regular" label="Reset" jsCode="javascript: resetForm();" /></td>
          <td>
            <widget class="\XLite\View\Button\Submit" label="Search" />
          </td>
        </tr>

      </table>

      </div>

    </td>
  </tr>

</table>

<script type="text/javascript" language="JavaScript 1.2">
<!--
visibleBox('1');
managedate('date_type');
-->
</script>

<script type="text/javascript" language="JavaScript 1.2" IF="isAdvancedOptionSelected()|mode=#search#&!getUsersCount()">
<!--
visibleBox('1');
-->
</script>

<script type="text/javascript" language="JavaScript 1.2" IF="getSearchParams(#user_type#)=#A#">
<!--
switchMembershipDisplaying(1);
-->
</script>

<script type="text/javascript" language="JavaScript 1.2" IF="getSearchParams(#date_period#)=#C#">
<!--
managedate('date', false);
-->
</script>

<script type="text/javascript" language="JavaScript 1.2" IF="!getSearchParams(#date_period#)=#C#">
<!--
managedate('date', true);
-->
</script>



</form>

<br />

<b>Note:</b> You can also <a href="admin.php?target=profile&mode=register">add a new user</a>.

