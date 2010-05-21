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
<p>From this page you can customize the appearance of the product link. Please insert HTML code from the form below into your Web site's HTML.</p>

<br>

<p class=TextTitle>Customize advanced HTML banner</p>

<form name=product_html_form action="cart.php" method=GET>
<input type="hidden" foreach="allparams,param,v" name="{param}" value="{v}"/>

<table border=0>
<tr>
    <td>
    <table border=0>
    <tr>
        <td>
        <select name=link_target>
            <option value="_blank" selected="link_target=#_blank#">Link opens new browser window</option>
            <option value="_top" selected="link_target=#_top#">Link in same browser window</option>
        </select>
        </td>
    </tr>
    <tr>
        <td>
        <select name=image>
            <option value=thumbnail selected="image=#thumbnail#">Product thumbnail image</option>
            <option value=image selected="image=#image#">Product image</option>
            <option value="" selected="image=##">No image</option>
        </select>
        </td>
    </tr>
    <tr>
        <td>
        <select name=border>
            <option value="1" selected="border=#1#">Border</option>
            <option value=0 selected="border=#0#">No border</option>
        </select>
        </td>
    </tr>
    <tr>
        <td>
        <select name=product_name>
            <option value=1 selected="product_name=#1#">Product name</option>
            <option value=0 selected="product_name=#0#">No product name</option>
        </select>
        </td>
    </tr>
    <tr>
        <td>
        <select name=description>
            <option value=brief_description selected="description=#brief_description#">Brief description</option>
            <option value=description selected="description=#description#">Full description</option>
            <option value="" selected="description=##">No description</option>
        </select>
        </td>
    </tr>
    <tr>
        <td nowrap>Message: <input type=text name=message value="{message:r}" size=35></td>
    </tr>
    <tr>
        <td nowrap>Background color: <input type=text name=background_color size=7 value="{background_color}"><td>
    </tr>    
    <tr>
        <td nowrap>Text color: <input type=text name=text_color value="{text_color}" size=7><td>
    </tr>    
    <tr>
        <td nowrap>Link color: <input type=text name=link_color value="{link_color}" size=7><td>
    </tr>    
    <tr>
        <td nowrap>Width: <input type=text name=width value="{width}" size=7><td>
    </tr>        
    <tr>
        <td nowrap>Height: <input type=text name=height value="{height}" size=7><td>
    </tr>        
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td><input type=submit name=update value="Update HTML">
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>Banner source:<br>
        <textarea rows=12 cols=50><widget class="XLite_Module_Affiliate_View_Banner" name=banner width="{inc(width,2)}" height="{inc(height,2)}" src="{getShopUrl(#cart.php#)}?target=product_banner&mode=advanced&product_id={product_id}&link_target={link_target}&image={image}&product_name={product_name}&description={description}&message={message}&border={border}&width={width}&height={height}&background_color={background_color}&text_color={text_color}&link_color={link_color}&partner={auth.profile.profile_id}"></textarea>
        </td>
    </tr>
    </table>
    </td>
    <td align=center valign=top width="100%">
    <table border=0 width="{inc(width,50)}">
    <tr><td align=center class=TextTitle>Preview:</td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td height="{inc(height,50)}" background="images/modules/Affiliate/pattern.gif" align=center>
        <widget name=banner>
        </td>
    </tr>
    </table>
    </td>
</tr>
</table>
</form>

<p><a href="{backUrl}"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Back to product list</a></p>
