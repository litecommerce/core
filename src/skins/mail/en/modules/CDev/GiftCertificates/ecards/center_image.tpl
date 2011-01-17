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
<STYLE type="text/css">

BODY,P,DIV,TH,TD,P,INPUT,SELECT,TEXTAREA {
        FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif; 
        COLOR: #000000; FONT-SIZE: 12px;
}
BODY { 
        MARGIN-TOP: 0 px; MARGIN-BOTTOM: 0 px; MARGIN-LEFT: 0 px; MARGIN-RIGHT: 0 px; 
        BACKGROUND-COLOR: #FFFFFF;
}
A:link {
        COLOR: #000000; TEXT-DECORATION: underline;
}
A:visited {
        COLOR: #000000; TEXT-DECORATION: underline;
}
A:hover {
        COLOR: #000000; TEXT-DECORATION: underline;
}
A:active  {
        COLOR: #000000; TEXT-DECORATION: underline;
}
.Title {
	font-size: 14 px; font-weight:bold; color: #ff0000;
}
</STYLE>
</HEAD>
<BODY LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 BOTTOMMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 >
<table border=0 width="100%" cellpadding=0 cellspacing=0>
<tr>
<td BACKGROUND="{gc.bordersDir}{gc.border}.gif" height="{gc.borderHeight}">&nbsp;</td>
</tr>
</table>
<table border=0 width="500" cellpadding=0 cellspacing=0 align="center">
<tr>
<td valign=top align=center>
<br>
<p align=center class=Title>Dear {gc.recipient}!<br>
{gc.purchaser} sent you a Gift Certificate for {price_format(gc.amount):h}</p>
<IMG src="{gc.imagesDir}{gc.ecard.image.url}" border=0 alt="">
<p align=center><b>{gc.greetings} {gc.recipient}!</b><br><br>
{gc.formattedMessage:h}<br>
<br>{gc.farewell} {gc.purchaser}
</p>
<p>
</td>
</tr>
<tr>
<td valign=top>
<table border=0 width="450" cellpadding=0 cellspacing=0 align="center">
<tr>
<td bgcolor="#dddddd">
<table border=0 width="100%" cellpadding=20 cellspacing=2 align="center">
<tr bgcolor="#ffffff">
<td>
<p align=center class=Title>GIFT CERTIFICATE ID: {gc.gcid}</p>
<p align=justify>
In order to redeem this gift certificate please follow these steps: 
<ol>
<li>Go to our site at <a href="{config.Company.company_website:h}">{config.Company.company_website}</a><br> 
<li>Add to cart some products<br> 
<li>Click 'checkout'<br> 
<li>Enter your personal details<br> 
<li>Select 'Gift Certificate' as payment method <br>
<li>Enter your Gift Certificate ID and click 'Submit order' button <br>
</ol>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
<p align="center"><b>Note:</b> This certificate was issued on {formatDate(gc.add_date)} and will expire on {formatDate(gc.expiration_date)}.</p>
<p align=center>
Thank you for using LiteCommerce shopping system<br>                               <br>
Phone: {config.Company.company_phone}<br>
Fax: {config.Company.company_fax}<br>
URL: {config.Company.company_website}<br><br>
</p>
<p>
</td>
</tr>
</table>
<table border=0 width="100%" cellpadding=0 cellspacing=0>
<tr>
<td BACKGROUND="{gc.bordersDir}{gc.border}_bottom.gif" height="{gc.bottomBorderHeight}">&nbsp;</td>
</tr>
</table>
