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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr valign="top">
    <td>
<b>UPS OnLine&reg; Tools</b>
{if:haveAccount}
<p>You have already registered with UPS.</p>
<p>To go through the licensing and registration process for UPS OnLine&reg; Tools again, click on the 'Register' button</p>
<p>To configure UPS Rates & Service Selection and Address Validation Tools click on the 'Configure' button</p>
{else:}
<p>You have not registered your UPS OnLine&reg; Tools.<p>
<p>To start the licensing and registration process for UPS OnLine&reg; Tools click on the 'Register' button.</p>
{end:}
    </td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
    <td colspan="2" align="right" nowrap>
    {if:haveAccount}
		<input type="submit" value=" Configure " class="DialogMainButton" OnClick="document.location='admin.php?target=ups_config'">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value=" Register " OnClick="document.location='admin.php?target=ups_online_tool&action=next'">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    {else:}
		<input type="submit" value=" Register " class="DialogMainButton" OnClick="document.location='admin.php?target=ups_online_tool&action=next'">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	{end:}
    </td>
</tr>
</table>
