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
<span IF="getMessage()" class="ErrorMessage">{getMessage()}<br/><br/></span>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr valign="top">
    <td>
        <form action="admin.php" method="post" name="reg_frm">
        <input FOREACH="allParams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
        <input type="hidden" name="action" value="next"/>
        <widget template="modules/CDev/UPSOnlineTools/settings/register.tpl">
        </form>
        <br/>
        To fill in the form by data from your personal profile click on the 'Fill From Profile' button. 
        <br/><br/>
    </td>
</tr>
<tr>
    <td colspan="2" align="right" nowrap>
		<input type="button" value=" Fill from profile " OnClick="document.location='admin.php?target=ups_online_tool&action=fill_from_profile';">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" value=" Next " class="DialogMainButton" OnClick="document.reg_frm.submit();">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value=" Cancel " OnClick="document.location='admin.php?target=ups_online_tool&action=cancel';">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    </td>
</tr>
</table>
