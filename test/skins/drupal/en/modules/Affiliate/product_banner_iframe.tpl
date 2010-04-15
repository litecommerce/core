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
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
</head>
<body>
{if:border=#1#}
<table cellSpacing=0 cellPadding=1 width="{width}" height="{height}" bgColor=#666666 border=0>
<tr>
    <td>
{end:}    
    <table cellSpacing=0 cellPadding=0 width="{inc(width,#-2#)}" height="{inc(height,#-2#)}" bgColor="#{background_color}" border=0>
    <tr>
        <td>
        {if:border=#1#}<center>{end:}
        <table width=100% height="{inc(height,#-2#)}" border=0 cellSpacing=0 cellPadding=3>
        <tr IF="image=#thumbnail#&product.hasThumbnail()" align=center>
            <td>
            <a href="cart.php?target=product&amp;product_id={product.product_id}&amp;partner={partner}&amp;referrer={referrer:u}" target="{link_target}"><img src="{product.thumbnailURL}" vspace=3 border=0 alt="{product.name:h}"></a>
            </td>               
        </tr>
        <tr IF="image=#image#&product.hasImage()" align=center>
            <td>
            <a href="cart.php?target=product&amp;product_id={product.product_id}&amp;partner={partner}&amp;referrer={referrer:u}" target="{link_target}"><img src="{product.imageURL}" vspace=3 border=0 alt="{product.name:h}"></a>
            </td>               
        </tr>
        <tr IF="product_name=#1#">
            <td vAlign=center height=15%>
            <center><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;partner={partner}&amp;referrer={referrer:r}" target="{link_target}"><font face=Arial color="#{link_color}" size=1>{product.name:h}</font></a>                 
            </td>               
        </tr>
        <tr IF="description=#brief_description#" height=15%>
            <td>
            <center><font face=Arial color="#{text_color}" size=-2>{product.brief_description:h}</font></center>
            </td>
        </tr>
        <tr IF="description=#description#" height=15%>
            <td>
            <center><font face=Arial color="#{text_color}" size=-2>{product.description:h}</font></center>
            </td>
        </tr>
        <tr IF="!message=##" height=15%>
            <td>
            <center><font face=Arial color="#{text_color}" size=1>{message:h}</font></center>                 
            </td>               
        </tr>
        <tr>
            <td height=5% align=absbottom>
            <center>
            <a href="cart.php?target=help&amp;mode=privacy_statement" target="{link_target}"><font face=Arial color=#a1a1a1 size=1>Privacy Information </font></center></a>
            </td>               
        </tr>             
        </table>           
        {if:border=#1#}</center>{end:}
        </td>         
    </tr>       
    </table>     
{if:border=#1#}    
    </td>   
</tr> 
</table>   
{end:}
</body>  
</html>
