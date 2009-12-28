	<table cellpadding="0" cellspacing="0" width="100%">	
	<tr>
		<td class=TableHead>Coupon</td>
		<td class=TableHead>Disc.</td>
		<td class=TableHead>Min.</td>
		<td class=TableHead colspan=2>Expires</td>
	<tr>
		<td height="25">
		<b><span IF="widget.DC.expired"><s>{widget.DC.coupon}</s></span><span IF="!widget.DC.expired"><span IF="widget.DC.status=#U#"><font class="ErrorMessage">{widget.DC.coupon}</font></span><span IF="!widget.DC.status=#U#"><span IF="widget.DC.status=#D#"><s>{widget.DC.coupon}</s></span><span IF="!widget.DC.status=#D#">{widget.DC.coupon}</span></span></span></b>
</td>
<td IF="widget.DC.type=#absolute#">{price_format(widget.DC.discount):h}</td>
<td IF="widget.DC.type=#percent#">{widget.DC.discount}%</td>
<td IF="widget.DC.type=#freeship#">Free shipping</td>
<td>{price_format(widget.DC.minamount):h}</td>
<td nowrap>
<span IF="!widget.DC.expired">{date_format(widget.DC.expire):h}</span>
<span IF="widget.DC.expired"><font class="ErrorMessage">{date_format(widget.DC.expire):h}</font></span>
</td>
<td IF="widget.clone"><input type="submit" value="Delete"></td>
</tr>
<tr>
<td colspan=7>
This coupon applies to 
<span IF="widget.DC.applyTo=#product#">
purchase of <a href="admin.php?target=product&product_id={widget.DC.product_id}"><u>{widget.DC.product.name}</u></a> product
</span>
<span IF="widget.DC.applyTo=#category#">
purchase of product(s) from <a href="admin.php?target=category&category_id={widget.DC.category_id}"><u>{widget.DC.category.name}</u></a> category
</span>
<span IF="widget.DC.applyTo=#total#">
orders greater than {price_format(widget.DC.minamount):h}
</span>
</td>
</tr>
</table>	
