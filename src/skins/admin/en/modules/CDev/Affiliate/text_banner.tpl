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
<form action="admin.php" method=POST name=add_modify_form enctype="multipart/form-data">
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}"/>
<input IF="mode=#add#" type=hidden name=action value="save_banner"/>
<input IF="mode=#modify#" type=hidden name=action value="update_banner"/>

<table border=0>
<tr>
    <td>
    <table border=0 cellpadding=3>
    <tr>
        <td nowrap>Banner name</td>
        <td class=Star>*</td>
        <td nowrap><input type=text name=name value="{name:r}" size=35 maxlength=255><widget class="\XLite\Validator\RequiredValidator" field="name"></td>
    </tr>
    <tr>
        <td valign=top>Image</td>
        <td valign=top class=Star>*</td>
        <td>
            <span IF="mode=#modify#"><img src="{getShopUrl(#cart.php#)}?target=image&action=banner_image&id={banner_id}&rnd={rand()}" border=0><br></span>
            <input type=file name=banner>
        </td>
    </tr>
    <tr>
        <td nowrap>Alt. tag</td>
        <td>&nbsp;</td>
        <td><input type=text name=alt value="{alt:r}" size=35 maxlength=255></td>
    </tr>
    <tr>
        <td>Appearance</td>
        <td>&nbsp;</td>
        <td>
        <select name=link_target>
            <option value="_blank" selected="link_target=#_blank#">Link opens new browser window</option>
            <option value="_top" selected="link_target=#_top#">Link in same browser window</option>
        </select>
        </td>
    </tr>
    <tr>
        <td valign=top>Text</td>
        <td>&nbsp;</td>
        <td><textarea name=body cols=35 rows=4>{body:r}</textarea></td>
    </tr>
    <tr>
        <td nowrap>Text alignment</td>
        <td>&nbsp;</td>
        <td>
        <select name=align> 
            <option value=bottom selected="align=#bottom#">Bottom</option>
            <option value=top selected="align=#top#">Top</option>
            <option value=left selected="align=#left#">Left</option>
            <option value=right selected="align=#right#">Right</option>
        </select>
        </td>
    </tr>
    <tr>
        <td>Availability</td>
        <td>&nbsp;</td>
        <td>
        <select name=enabled>
            <option value=1 selected="enabled=#1#">Enabled</option>
            <option value=0 selected="enabled=#0#">Disabled</option>
        </select>
        </td>
    </tr>
    <tr>
        <td colspan=2>&nbsp;</td>
        <td>
        <input type=submit name=save value="Save banner">
        </td>
    </tr>
    </table>
    </td>
    <td IF="mode=#modify#" align=center valign=top width="100%">
    <font class=AdminHead>Preview:</font><br><br>
    <widget class="\XLite\Module\CDev\Affiliate\View\Banner" mode="modify" type="js" banner="{banner}">
    </td>
</tr>    
<tr><td colspan=2>&nbsp;</td></tr>
<tr>
    <td colspan=2><a href="admin.php?target=banners"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle"> List all banners</a></p></td>
</tr>
</table>
