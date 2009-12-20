<widget class="CPager" data="{wishlists}" name="pager">
<br>
<table border="0">
<tr class="TableHead">
    <th>&nbsp;</th>
    <th>&nbsp;ID</th>
    <th nowrap>&nbsp;Creation date</th>
	<th>E-mail</th>
    <th>Customer</th>
	<th></th>
</tr>
<tr FOREACH="pager.pageData,wishlist">
	<td><input type="checkbox" name="wishlist_id[]" value="{wishlist.wishlist_id:h}"></td>
	<td><a href="admin.php?target=wishlist&wishlist_id={wishlist.wishlist_id:h}"><u>{wishlist.wishlist_id:h}</u></a></td>
	<td align="center">{date_format(wishlist.date)}</td>	
	<td><a href="admin.php?target=profile&profile_id={wishlist.profile.profile_id}"><u>{wishlist.profile.login:h}</u></a></td>
	<td nowrap>{wishlist.profile.billing_lastname:h} {wishlist.profile.billing_firstname:h}</td>
	<td nowrap><a href="admin.php?target=wishlist&wishlist_id={wishlist.wishlist_id:h}"><u>See details</u>&nbsp;&gt;&gt;</a></td>
</tr>
</table>
<script>
function delete_warning() 
{
	if (confirm('You are about to delete a customer wish list.\n\nAre you sure you want to delete it?')) { 
		document.wishlist_form.action.value = 'delete'; 
		document.wishlist_form.submit(); 
		return true;
	}
	return false;
}
</script>
<input type="button" value="Delete"  onclick="javascript: delete_warning();">
</form>
