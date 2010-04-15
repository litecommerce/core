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
<script language="Javascript" type="text/javascript">
function OnLoadLicense()
{
	obj = document.getElementById('wait_message');
	if (obj)
		obj.style.display = 'none';
}
</script>

<span IF="error=#license#" class="ErrorMessage">You should agree to the UPS OnLine&reg; Tools Access License Agreement to continue registering with UPS!<br/><br/></span>
<span IF="error=#http#" class="ErrorMessage">The UPS OnLine&reg; Tools Access License Agreement can not be received from UPS server or no connection to the Internet. Please try again later.<br/><br/></span>

<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody id="wait_message" style="display: ;">
<tr valign="top">
	<td><br><font class="ErrorMessage">Please, wait. Data transmission in progress.</font><br><br></td>
</tr>

</tbody>
<tr valign="top">
    <td>
        <iframe src="admin.php?target=ups_online_tool&action=showlicense" width="100%" height="400" scrolling="auto" frameborder="1" align="center" name="license" id="license" OnLoad="OnLoadLicense();">
        </iframe>
    </td>
</tr>
<tr>
    <td>
        <br>
        <form action="admin.php" method="post" name="agree_frm">
        <input FOREACH="allParams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
        <input type="hidden" name="action" value="next">
        <input type="hidden" name="confirmed" id="confirmedLicense" value="N">
        </form>
    </td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
    <td colspan="2" align="right" nowrap>
		<span id="manage_buttons" style="display: none;">
	        <input type="button" value=" Print " OnClick="window.open('admin.php?target=popup_ups_online_tool&mode=print','print_agreement','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no, width=640, height=480,screenX=150,screenY=150,top=150,left=150');">
    	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	<input type="submit" value=" Next " class="DialogMainButton" OnClick="document.agree_frm.submit();">
	        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</span>
        <input type="button" value=" Cancel " OnClick="document.location='admin.php?target=ups_online_tool&action=cancel';">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </td>
</tr>
</table>
