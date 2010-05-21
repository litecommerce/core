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
{if:!config.General.admin_zone_sbbe_enabled}
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td class="SidebarBorder">
 <table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>
    <td class="SidebarTitle" background="images/sidebartitle_back.gif">&nbsp;<widget template="{dir}/image.tpl"/>&nbsp;&nbsp;<widget template="{dir}/head.tpl"/></td>
 </tr>
 <tr class="SidebarBox">
    <td>
    <table border="0" cellpadding="5" cellspacing="0" width="100%">
    <tr><td>
    <widget template="{dir}/body.tpl"/>
    <img src="images/spacer.gif" width=150 height=5>
    </td>
    </tr>
    </table>
    </td>
 </tr>
 </table>
</td>
</tr>
</table>
<br>
{else:}
<SCRIPT language="JavaScript">

{getSidebarBoxStatus(dir)}

</SCRIPT>

<SCRIPT language="JavaScript">
function ChangeBoxBody(bodyId)
{
	sidebar_box_statuses[bodyId] = (sidebar_box_statuses[bodyId] == 1) ? "0" : "1";
    SetBoxBody(bodyId);

	var elm = document.getElementById("sidebar_box_action_"+bodyId);
	if (elm)
	{
		elm.src = "{xlite.getShopUrl(#admin.php#,config.Security.admin_security)}?target=sbjs&mode=change_sidebar_box_status&sidebar_box_id="+bodyId+"&time="+(new Date().getTime());
	}
}

function SetBoxBody(bodyId)
{
	var elm = document.getElementById("sidebar_box_body_"+bodyId);
	if (elm)
	{
		elm.style.display = (sidebar_box_statuses[bodyId] == 1) ? "" : "none";
	}
	elm = document.getElementById("sidebar_box_button_"+bodyId);
	if (elm)
	{
		var newSrc = elm.src;
		if (sidebar_box_statuses[bodyId] == 1)
		{
			newSrc = newSrc.replace(/open_sidebar_box/, "close_sidebar_box");
			newSrc = newSrc.replace(/close_sidebar_box/, "close_sidebar_box");
			elm.title = "Click to hide this box";
		}
		else
		{
			newSrc = newSrc.replace(/open_sidebar_box/, "open_sidebar_box");
			newSrc = newSrc.replace(/close_sidebar_box/, "open_sidebar_box");
			elm.title = "Click to show this box";
		}
		elm.src = newSrc;
	}
}
</SCRIPT>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td class="SidebarBorder">
 <table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>
    <td class="SidebarTitle" background="images/sidebartitle_back.gif">
    	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
    		<td width="100%">&nbsp;<widget template="{dir}/image.tpl"/>&nbsp;&nbsp;<widget template="{dir}/head.tpl"/></td>
    		<td><A href="javascript: ChangeBoxBody('{strMD5(dir)}')" onClick="this.blur()"><IMG src="images/close_sidebar_box.gif" width=14 height=14 border="0" id="sidebar_box_button_{strMD5(dir)}" title="Click to hide this box"></A>&nbsp;</td>
		</tr>
		</table>
	</td>
 </tr>
 <tr class="SidebarBox">
    <td width="100%">
    <table border="0" cellpadding="5" cellspacing="0" width="100%">
    <tr><td style="display:;" id="sidebar_box_body_{strMD5(dir)}">
    <widget template="{dir}/body.tpl"/>
    <img src="images/spacer.gif" width=150 height=5>
    </td>
	<SCRIPT language="JavaScript">SetBoxBody("{strMD5(dir)}")</SCRIPT>
    </tr>
    </table>
    </td>
 </tr>
 </table>
</td>
</tr>
<tr><td><img src="images/spacer.gif" width=167 height=1><IMG src="" width=0 height=0 border="0" id="sidebar_box_action_{strMD5(dir)}"></td></tr>
</table>
<br>
{end:}
