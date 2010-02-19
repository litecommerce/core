{* SVN $Id$ *}
<br />
<table width="100%">
  <tr>

    <td align="left" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
      <widget class="XLite_View_Button" label="Clear cart" type="button" href="{buildURL(#cart#,#clear#)}">
    </td>

    <td width=20>&nbsp;</td>

    <td align="left" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
      <widget class="XLite_View_Button" label="Update cart" type="button">
    </td>

    <td width="100%">&nbsp;</td>

    <td align="right" nowrap>
      <widget class="XLite_View_Button" label="Continue shopping" type="button_link" href="{session.continueURL}">
    </td>

    <td width=20>&nbsp;</td>

    <td align="right" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
      <widget class="XLite_View_Button" label="CHECKOUT" type="button" href="{buildURL(#cart#,#checkout#)}">
    </td>

  </tr>
</table>
