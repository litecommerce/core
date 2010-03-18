You are privileged to download this product free of charge.<br>
Click the link<span IF="!product.egoodsNumber=#1#">s</span> below to download and save the file<span IF="!product.egoodsNumber=#1#">s</span>:
<table border="0" cellspacing="3" cellpadding="3">
<tbody FOREACH="product.egoods,egood">
<tr>
	<td>&nbsp;&nbsp;</td>
	<td>&bull;</td>
	<td>
		<a href="{xlite.getShopUrl(#cart.php?target=download&action=download&file_id=#):r}{egood.file_id}" onClick="this.blur()"><FONT class="Button"><U>{egood.fileName}</U></FONT></a>
	</td>
</tr>
</tbody>
</table>

