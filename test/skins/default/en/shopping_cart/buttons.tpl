{* SVN $Id$ *}
<br />
<table width="100%">
  <tr>

    <td align="left" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
	  <widget class="XLite_View_Button_Regular" label="Clear cart" action="clear" />
    </td>

    <td width=20>&nbsp;</td>

    <td align="left" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
	  <widget class="XLite_View_Button_Submit" label="Update cart" />
    </td>

    <td width="100%">&nbsp;</td>

    <td align="right" nowrap>
	  <widget class="XLite_View_Button_Link" label="Continue shopping" location="{session.continueURL}" />
    </td>

    <td width=20>&nbsp;</td>

    <td align="right" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
	  <widget class="XLite_View_Button_Regular" label="Checkout" action="checkout" />
    </td>

  </tr>
</table>
