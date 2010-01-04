<form name="wishlist_form" action="admin.php" method="GET">
<input type="hidden" name="target" value="wishlists">
<input type="hidden" name="mode" value="search">
<input type="hidden" name="action" value="">

<table border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td class=FormButton width="100px">List ID:</td>
		<td><input type="text" name="start_id" size="5" value="{start_id:h}">&nbsp;-&nbsp;<input type="text" name="end_id" size="5" value="{end_id:h}"></td>
	</tr>
    <tr>
        <td class=FormButton>E-mail:</td>
        <td><input type="text" name="email" size="14" value="{email:h}"></td>
    </tr>
    <tr>
        <td class=FormButton>SKU:</td>
        <td><input type="text" name="sku" size="14" value="{sku:h}"></td>
    </tr>
    <tr>
        <td class=FormButton>Product name:</td>
        <td><input type="text" name="product" size="14" value="{product:h}"></td>
    </tr>
	<tr>
		<td class=FormButton noWrap height=10>Creation date from:</td>
		<td height=10><widget class="XLite_View_Date"field="startDate"></td>
	</tr>
    <tr>
        <td class=FormButton noWrap height=10>Creation date through:</td>
        <td height=10><widget class="XLite_View_Date"field="endDate"></td>
    </tr>
	<tr>
		<td></td>
		<td align="left"><input type="submit" value="Search"></td>
	</tr>
</table>

