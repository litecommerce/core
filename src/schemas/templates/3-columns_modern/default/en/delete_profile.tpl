<table>
<tr IF="submode=#warning#">
<!-- Show delete profile confirmation dialog -->
<td>
<span class="Text">
Do you really want to delete your profile?
</span>
<p>
<table border=0>
<tr>
<td><widget class="CButton" label=" Yes " href="cart.php?target=profile&action=delete"></td>
<td nowrap>&nbsp;&nbsp;&nbsp;</td>
<td><widget class="CButton" label=" No " href="cart.php?target=profile&mode=delete&submode=cancelled"></td>
</tr>
</table>
</td>
</tr>

<tr IF="submode=#confirmed#">
<!-- Show deleted profile message -->
<td>
Your profile was deleted successfully.
</td>
</tr>

<tr IF="submode=#cancelled#">
<!-- Show cancel profile delete message -->
<td>
Your profile has not been deleted.
</td>
</tr>
</table>
