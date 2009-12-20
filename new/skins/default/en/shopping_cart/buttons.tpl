<p>
<table border=0 width="100%">
<tr>
<td align="left" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
<widget class="CButton" label="Clear cart" href="javascript: document.cart_form.action.value='clear'; document.cart_form.submit()" font="ErrorMessage">
</td>
<td width=20>&nbsp;</td>
<td align="left" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
<widget class="CButton" label="Update cart" href="javascript: document.cart_form.action.value='update'; document.cart_form.submit()" font="FormButton">
</td>
<td width="100%">&nbsp;</td>
<td align="right" nowrap>
<widget class="CButton" label="Continue shopping" href="{session.continueURL}" font="FormButton">
</td>
<td width=20>&nbsp;</td>
<td align="right" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
<widget class="CButton" label="CHECKOUT" href="javascript: document.cart_form.action.value='checkout'; document.cart_form.submit()" font="FormButton">
</td>
</tr>
</table>
