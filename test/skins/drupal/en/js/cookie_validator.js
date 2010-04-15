/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

function isSetCookie()
{
	return navigator.cookieEnabled;
}

if (!isSetCookie()) {
	document.write("<table border=0 width=500 cellpadding=2 cellspacing=0 align=center>");
	document.write("<tr>");
	document.write("<td align=center class=ErrorMessage nowrap>");
	document.write("This site requires cookies to function properly.<br>Please enable cookies in your web browser.");
	document.write("</td>");
	document.write("</tr>");
	document.write("</table>");
}
