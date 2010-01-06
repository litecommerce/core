<html>
<body>
<p align="justify">
You are receiving this e-mail notification from {config.Company.company_name} 
because {sender_name} has suggested that you might be interested
in the following product:
</p>
<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td colspan="2" align="left"> {product.name} </td>
</tr>
<tr>
	<td align="left">Description:</td>
	<td>{product.brief_description:h}</td>
</tr>
<tr>
	<td>Price:</td> 
	<td>{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></td>
</tr>
<tr>
	<td colspan="2">Please click on the link provided below to see 
the detailed information on "{product.name}":</td>
</tr>
<tr>
	<td colspan="2"><a href="{url:h}">{url:h}</a></td>
</tr>	
</table>

{signature:h}
</body>
</html>
