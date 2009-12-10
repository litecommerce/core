{* Check gift certificate *}
If you have an existing Gift certificate you can check its current amount and status using the form below.
<br><br>
<font class="ErrorMessage" IF="notFound">The specified Gift certificate is not found.</font>
<FORM name="gccheckform" action="cart.php" method="GET">
<input type="hidden" name="target" value="check_gift_certificate">
<table border="0"><tr><td>
Gift certificate:&nbsp;
</td><td><INPUT type="text" size="25" maxlength="16" name="gcid" value="{foundgc.gcid:r}">
</td><td><widget class="CButton" label="Find" href="javascript: document.gccheckform.submit();">
</td></tr></table>
</FORM>

<table border="0" IF="found">
<tr><td colspan="2">
<hr size="1" noshade>
</td></tr>
<tr>
<td><b>Gift Certificate ID:</b></td>
<td>{foundgc.gcid}</td>
</tr>
<tr>
<td><b>Amount:</b></td>
<td>{price_format(foundgc,#amount#):h}</td>
</tr>
<tr>
<td><b>Available:</b></td>
<td>{price_format(foundgc,#debit#):h}</td>
</tr>
<tr>
<td><b>Status:</b></td>
<td>
<span IF="isSelected(foundgc,#status#,#P#)">Pending</span>
<span IF="isSelected(foundgc,#status#,#A#)">Active</span>
<span IF="isSelected(foundgc,#status#,#D#)">Disabled</span>
<span IF="isSelected(foundgc,#status#,#U#)">Used</span>
</td>
</tr>
</table>

