<tbody id="optionsException" IF="invalid_options">
<tr>
<td colspan=2 class="ErrorMessage">The option combination you selected:</td>
</tr>

<tr FOREACH="invalid_options,option,value">
<td width="30%"><b>{option:h}:</b></td>
<td>{value:h}</td>
</tr>

<tr>
<td colspan=2 class="ErrorMessage">is not available. Please make another choice.</td>
</tr>

<tr>
<td colspan=2>&nbsp;</td>
</tr>
</tbody>
