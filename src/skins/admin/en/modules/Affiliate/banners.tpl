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
<p>From this page you can view/modify existing banners and add new banners.

<form action="admin.php" method=GET>
<input type=hidden name=target value=banner>
<input type=hidden name=mode value=add>

<table border=0>
<tr><td colspan=2 class=AdminTitle>Add new banner</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>Select banner type:</td></tr>
<tr>
    <td width=10><input type=radio name=type value=image checked></td>
    <td>Image banner</td>
</tr>    
<tr>
    <td width=10><input type=radio name=type value=text></td>
    <td>Text link banner</td>
</tr>
<tr>
    <td width=10><input type=radio name=type value=rich></td>
    <td>Media rich banner (javascript, flash, etc.)</td>
</tr>    
<tr><td colspan=2><input type=submit value=" Add "></td></tr>
</table>
</form>

<br>
<p>
<table IF="banners" border=0>
<tr><td>&nbsp;</td></tr>
<tr><td class=AdminHead>Available banners</td></tr>
<tr><td>&nbsp;</td></tr>
<tbody FOREACH="banners,bidx,banner">
<tr><td>&nbsp;</td></tr>
<tr><td><b>&quot;{banner.name:h}&quot;</b> banner <font IF="!banner.enabled" color=red>(inactive)</font></td></tr>
<tr>
    <td>
    <table border=1 cellspacing=0 cellpadding=8 background="images/pattern.gif">
    <tr>
    <td>
    <widget class="\XLite\Module\Affiliate\View\Banner" type="js" banner="{banner}">
    </td>
    </tr>
    </table>
    </td>
</tr>    
<tr>
    <td>
    <input type=button name=modify value=Modify onclick="document.location='admin.php?target=banner&mode=modify&type={banner.type}&banner_id={banner.banner_id}'">&nbsp;
    <input type=button name=delete value=Delete onclick="document.location='admin.php?target=banners&action=delete&banner_id={banner.banner_id}'">
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
</tbody>
</table>
