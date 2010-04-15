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
<head><title>Product is now available</title></head>
<body>
<p>Dear <span IF="ntf.person_info">{ntf.person_info:h}</span><span IF="!ntf.person_info">Madam/Sir</span>,</p>

<p>you are receiving this message because you elected to be notified when "{ntf.product.name}" is available at our store.</p>

<p>We are glad to inform you that we now have "{ntf.product.name}" in stock:</p>

<table border=0 cellpadding=0 cellspacing=0>
<tr>
	<td>Product:</td>
	<td>&nbsp;</td>
	<td><b>{ntf.product.name}</b></td>
</tr>
<tr IF="ntf.product.productOptionsStr">
	<td>Options:</td>
	<td>&nbsp;</td>
	<td><b>{ntf.product.productOptionsStr}</b></td>
</tr>
<tr IF="ntf.quantity">
	<td>Quantity:</td>
	<td>&nbsp;</td>
	<td><b>was <b>{ntf.quantity}</b>, now is <b>{ntf.product.quantity}</b></b></td>
</tr>
</table>

<p>{signature:h}</p>
</body>
</html>
