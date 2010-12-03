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
<P>
<TABLE border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
    <td height="20"  class=DialogTitle background="images/dialog_bg1.gif" valign="bottom" IF="!toneHeader">&nbsp;&nbsp;UPS OnLine&reg; Tools {head}</td>
	<td height="20"  class=DialogTitle background="images/sidebartitle_back.gif" valign="bottom" IF="toneHeader">&nbsp;&nbsp;UPS OnLine&reg; Tools {head}</td>
</tr>
<tr>
    <td class=DialogBorder>
        <table border="0" cellpadding="10" cellspacing="0" width="100%">
	        <tr>
				<td class=DialogBox align="center" valign="top"><widget template="modules/CDev/UPSOnlineTools/settings/ups_logo.tpl"></td>
				<td class=DialogBox width="100%"><widget template="{body}"></td>
			</tr>
			<tr>
				<td colspan="2" class=DialogBox><widget template="modules/CDev/UPSOnlineTools/settings/bottom.tpl"></td>
			</tr>
        </table>
    </td>
</tr>
</table>
