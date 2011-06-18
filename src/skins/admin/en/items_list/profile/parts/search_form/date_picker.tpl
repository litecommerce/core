{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Date selector
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.profile.search.search_form", weight="900")
 *}

<tr id="date_period_box">
  <td class="table-label">{t(#During the period#)}</td>
  <td>
    <table cellpadding="2" cellspacing="2">

      <tr>
        <td style="width:5;"><input type="radio" id="date_period_M" name="date_period" value="M"{if:getParam(#date_period#)=#M#|!getParam(#date_period#)} checked="checked"{end:} onclick="javascript: managedate('date',true)" /></td>
        <td colspan="2" class="OptionLabel"><label for="date_period_M">This month</label></td>
      </tr>

      <tr>
        <td style="width:5;"><input type="radio" id="date_period_W" name="date_period" value="W"{if:getParam(#date_period#)=#W#} checked="checked"{end:} onclick="javascript: managedate('date',true)" /></td>
        <td colspan="2" class="OptionLabel"><label for="date_period_W">This week</label></td>
      </tr>

      <tr>
        <td style="width:5;"><input type="radio" id="date_period_D" name="date_period" value="D"{if:getParam(#date_period#)=#D#} checked="checked"{end:} onclick="javascript: managedate('date',true)" /></td>
        <td colspan="2" class="OptionLabel"><label for="date_period_D">Today</label></td>
      </tr>

      <tr>
        <td style="width:5;"><input type="radio" id="date_period_C" name="date_period" value="C"{if:getParam(#date_period#)=#C#} checked="checked"{end:} onclick="javascript: managedate('date',false)" /></td>
        <td class="OptionLabel"><label for="date_period_C">From</label></td>
        <td><widget class="\XLite\View\DatePicker" field="startDate" value="{getParam(#startDate#)}" /></td>
      </tr>

      <tr>
        <td></td>
        <td class="OptionLabel">Through</td>
        <td><widget class="\XLite\View\DatePicker" field="endDate" value="{getParam(#endDate#)}" /></td>
      </tr>

    </table>
  </td>
</tr>
