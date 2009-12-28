<tr>
	<td valign="top" class="FormButton">Disable Google Checkout for this product</td>
	<td valign="top" class=ProductDetails>
		<select name="google_disabled">
			<option value="0" {if:!product.google_disabled}selected{end:}>No</option>
			<option value="1" {if:product.google_disabled}selected{end:}>Yes</option>
		</select>
	</td>
</tr>
