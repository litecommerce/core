<html>
<body>
<p align="justify">
You are receiving this message from {config.Company.company_name:h} 
because our customer {customer:h} requested that we
let you know what is on his(her) wish list.

</p>
<p align="left">
These are the items {customer:h} is interested in:
</p>

<table foreach="items,product" border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td colspan="2" align="left">{product.name}</td>
</tr>
<tr>
	<td align="left">Description:</td>
	<td>{product.brief_description:h}</td>
</tr>
<tr>
	<td>Price:</td> 
	<td>{price_format(product,#price#):h}</td>
</tr>
<tr>
	<td colspan="2">Please click the link provided below to see the detailed information 
on "{product.name}":</td>
</tr>
<tr>
	<td colspan="2"><a href="{xlite.shopURL(product.url):h}">{xlite.shopURL(product.url):h}</a></td>
</tr>	
</table>
{signature:h}
</body>
</html>
