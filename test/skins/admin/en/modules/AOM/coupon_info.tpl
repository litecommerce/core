{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table cellpadding="0" cellspacing="0" width="100%">	
	<tr>
		<td class=TableHead>Coupon</td>
		<td class=TableHead>Disc.</td>
		<td class=TableHead>Min.</td>
		<td class=TableHead colspan=2>Expires</td>
	<tr>
		<td height="25">
		<b><span IF="DC.expired"><s>{DC.coupon}</s></span><span IF="!DC.expired"><span IF="DC.status=#U#"><font class="ErrorMessage">{DC.coupon}</font></span><span IF="!DC.status=#U#"><span IF="DC.status=#D#"><s>{DC.coupon}</s></span><span IF="!DC.status=#D#">{DC.coupon}</span></span></span></b>
</td>
<td IF="DC.type=#absolute#">{price_format(DC.discount):h}</td>
<td IF="DC.type=#percent#">{DC.discount}%</td>
<td IF="DC.type=#freeship#">Free shipping</td>
<td>{price_format(DC.minamount):h}</td>
<td nowrap>
<span IF="!DC.expired">{date_format(DC.expire):h}</span>
<span IF="DC.expired"><font class="ErrorMessage">{date_format(DC.expire):h}</font></span>
</td>
<td IF="clone"><input type="submit" value="Delete"></td>
</tr>
<tr>
<td colspan=7>
This coupon applies to 
<span IF="DC.applyTo=#product#">
purchase of <a href="admin.php?target=product&product_id={DC.product_id}"><u>{DC.product.name}</u></a> product
</span>
<span IF="DC.applyTo=#category#">
purchase of product(s) from <a href="admin.php?target=category&category_id={DC.category_id}"><u>{DC.category.name}</u></a> category
</span>
<span IF="DC.applyTo=#total#">
orders greater than {price_format(DC.minamount):h}
</span>
</td>
</tr>
</table>
