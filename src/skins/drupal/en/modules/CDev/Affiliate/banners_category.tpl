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
<br>
<form action="cart.php" method=GET>
<input type="hidden" foreach="allparams,param,v" name="{param}" value="{v}"/>
<font class=TextTitle>Select category:</font>
<br><br>
<widget class="\XLite\View\CategorySelect" template="modules/CDev/Affiliate/select_category.tpl" fieldName="category_id">
<br><br>
<input type=submit value="Build links">
</form>

<p IF="category_id">
<br><br>
    <table border=0>
    <tr class=TableHead>
        <td>Text link:</td>
        <td align=center width="100%">Preview:</td>
    </tr>
    <tr>
        <td>
        <textarea cols=50 rows=4><a href="{getShopUrl(#cart.php#)}?target=category&category_id={category_id}&partner={auth.profile.profile_id}">{category.name:h}</a></textarea>
        </td>
        <td align=center>
        <a href="{getShopUrl(#cart.php#)}?target=category&category_id={category_id}&partner={auth.profile.profile_id}">{category.name:h}</a>
        </td>
    </tr>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr class=TableHead>
        <td>Small graphic link:</td>
        <td align=center width="100%">Preview:</td>
    </tr>
    <tr>
        <td valign=top>
        <textarea cols=50 rows=4><a href="{getShopUrl(#cart.php#)}?target=category&category_id={category_id}&partner={auth.profile.profile_id}">{category.name:h}<img src="{getShopUrl(#cart.php#,secure,#1#)}?target=image&action=category&id={category_id}" border=0></a></textarea>
        </td>
        <td align=center>
        <a href="{getShopUrl(#cart.php#)}?target=category&category_id={category_id}&partner={auth.profile.profile_id}"><img src="{getShopUrl(#cart.php#,secure,#1#)}?target=image&action=category&id={category_id}" border=0></a>
        </td>
    </tr>
    </table>
</p>
