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
