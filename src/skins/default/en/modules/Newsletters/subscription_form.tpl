<tr IF="newsLists">
    <td colspan="4">&nbsp;</td>
</tr>
<tr IF="newsLists" valign="middle">
    <td colspan="4"><b>Newsletters</b><br><hr size="1" noshade></td>
</tr>
<tbody FOREACH="newsLists,nlid,nl">
<tr valign="middle">
    <td align="right"><input type=checkbox name="list_ids[]" value="{nl.list_id}" checked="{isListSelected(nl.list_id)}"></td>
    <td>&nbsp;</td>
    <td><b>{nl.name:h}</b></td>
    <td>&nbsp;</td>
</tr>
<tr IF="nl.description">
    <td colspan=2>&nbsp;</td>
    <td colspan=2>{nl.description:h}</td>
</tr>
<tr>
    <td colspan=4>&nbsp;</td>
</tr>
</tbody>
