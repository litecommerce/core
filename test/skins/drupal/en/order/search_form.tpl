{* SVN $Id$ *}
<form action="{buildURLPath(#order_list#)}" method="get" name="order_search_form">
  <input FOREACH="buildURLArguments(#order_list#),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />
  <input type="hidden" name="mode" value="search" />

  <table>
    <tbody>

      <tr>
        <td class="FormButton" nowrap height="10">Order id</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10">
          <input size="6" name="order_id1" value="{order_id1}"> - <input size="6" name="order_id2" value="{order_id2}">
        </td>
      </tr>

      <tr>
        <td class="FormButton" nowrap height="10">Order status:</td>
        <td width="10" height="10"><font class="ErrorMessage">*</font></td>
        <td height="10">
          <widget class="XLite_View_StatusSelect" field="status" allOption>
        </td>
      </tr>

      <tr>
        <td class="FormButton" nowrap height="10">Order date from:</td>
        <td width="10" height="10"><font class="ErrorMessage">*</font></td>
        <td height="10">
          <widget class="XLite_View_Date" field="startDate">
        </td>
      </tr>

      <tr>
        <td class="FormButton" nowrap height="10">Order date through:</td>
        <td width="10" height="10"><font class="ErrorMessage">*</font></td>
        <td height="10">
          <widget class="XLite_View_Date" field="endDate">
        </td>
      </tr>

      <tr>
        <td class="FormButton" width="78">&nbsp;</td>
        <td width="10">&nbsp;</td>
        <td height="30">
          <widget class="XLite_View_Button" label="Search" type="button">
        </td>
      </tr>

    </tbody>
  </table>

</form>
