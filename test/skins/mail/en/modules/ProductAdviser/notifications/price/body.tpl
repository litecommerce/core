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
<html>
<head><title>Product price is fall</title></head>
<body>

<p>Dear <span IF="ntf.person_info">{ntf.person_info:h}</span><span IF="!ntf.person_info">Madam/Sir</span>,</p>

<p>you are receiving this message because you elected to be notified of the price changes on "{ntf.product.name}" at our store.</p>

<p>We are glad to inform you that our price on "{ntf.product.name}" has dropped down:</p>

<table border=0 cellpadding=0 cellspacing=0>
<tr>
	<td>Product:</td>
	<td>&nbsp;</td>
	<td><b>{ntf.product.name}</b></td>
</tr>
<tr>
	<td>Price:</td>
	<td>&nbsp;</td>
	<td><b>{price_format(ntf.product.listPrice)}<font class="ProductPriceTitle"> {ntf.product.priceMessage:h}</font></b>&nbsp;(<b style="text-decoration:line-through">{price_format(ntf.listPrice)}</b>)</td>
</tr>
</table>

<p>{signature:h}</p>
</body>
</html>
