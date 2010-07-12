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
<script type="text/javascript">
<!--
function visibleBox(id, status)
{
    var Element = document.getElementById(id);
    if (Element) {
        Element.style.display = ((status) ? "" : "none");
    }
}

function ShowNotes()
{
    visibleBox("notes_url", false);
    visibleBox("notes_body", true);
}

function setChecked(form, input, check)
{
    var elements = document.forms[form].elements[input];
    for (var i = 0; i < elements.length; i++) {
        if (!elements[i].disabled) elements[i].checked = check;
    }
}

function setHeaderChecked()
{
    var Element = document.getElementById("waiting_ips");
    if (Element && !Element.checked) {
        Element.checked = true;
    }
}

function deleteAllowedIPs()
{
  document.allowed_ips_form.elements.namedItem('action').value='delete_allowed_ip';
	document.allowed_ips_form.submit();
}

function updateAllowedIPs()
{
  document.allowed_ips_form.elements.namedItem('action').value='update_allowed_ip';
  document.allowed_ips_form.submit();
}

function allowCurrentIP()
{
    var ip_string = document.getElementById('current_ip').innerHTML;
    var byte_1 = ip_string.substring(0, ip_string.indexOf('.'));
    ip_string = ip_string.substring(ip_string.indexOf('.')+1, ip_string.length);
    var byte_2 = ip_string.substring(0, ip_string.indexOf('.'));
    ip_string = ip_string.substring(ip_string.indexOf('.')+1, ip_string.length);
    var byte_3 = ip_string.substring(0, ip_string.indexOf('.'));
    var byte_4 = ip_string.substring(ip_string.indexOf('.')+1, ip_string.length);
    document.add_ip_form.byte_1.value = byte_1;
    document.add_ip_form.byte_2.value = byte_2;
    document.add_ip_form.byte_3.value = byte_3;
    document.add_ip_form.byte_4.value = byte_4;
    document.add_ip_form.comment.value = 'Default admin IP';
    document.add_ip_form.submit();
}
// -->
</script>

Use this section to manage allowed and awaiting IP addresses
<span id="notes_url" style="display:"><a href="javascript:ShowNotes();" class="NavigationPath"><b>How to use this section &gt;&gt;&gt;</b></a></span>
<span id="notes_body" style="display: none">
<br><br>
The optional 'comment' field can be used for additional
information about the corresponding IP address.<br />
Default admin IP is the IP address of the admin who enabled admin IP protection system.<br />
</span>
<hr><br>

Your current IP: <b id="current_ip">{currentIP}</b>
{if:currentIpValid}
 <span class="SuccessMessage">(allowed)</span>
{else:}
 <span class="ErrorMessage">(not allowed)</span>
&nbsp;&nbsp;&nbsp;<a href="javascript: allowCurrentIP();" class="FormButton"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> add to allowed IPs list</a>
{end:}

{if:waitingList}
<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<TR>
    <TD colspan=2>
        <table cellspacing=0 cellpadding=0 border=0 width="100%">
        <tr>
            <td colspan=2>&nbsp;</td>
        </tr>
        <tr>
            <td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Awaiting IPs&nbsp;&nbsp;&nbsp;</td>
            <td width=100%>&nbsp;</td>
        </tr>
        <tr>
            <td class="SidebarTitle" align=center colspan=2 height=3></td>
        </tr>
        </table>
    </TD>
</TR>
</table>
<form action="admin.php" method="POST" name="waiting_ips_form">
<input FOREACH="allparams,key,val" type="hidden" name="{key}" value="{val:r}" />
<input type="hidden" name="action" value="" />
<table border="0" cellspacing="1" cellpadding="2" class="CenterBorder">
<tr class="TableHead">
    <th><input id="waiting_ips" type="checkbox" onClick="javascript: setChecked('waiting_ips_form', 'waiting_ips', this.checked);" /></th>
    <th nowrap class="TableHead">IP</th>
    <th nowrap class="TableHead">First login date</th>
    <th nowrap class="TableHead">Last login date</th>
    <th nowrap class="TableHead">Login attempts</th>
</tr>
<tr FOREACH="waitingList,id,waiting_ip" class="{getRowClass(id,#DialogBox#,#TableRow#)}">
    <td align="center"><input id="waiting_ips" type="checkbox" name="waiting_ips[]" value="{waiting_ip.id}" /></td>
    <td nowrap align=left>&nbsp;{waiting_ip.ip}&nbsp;</td>
    <td nowrap align=left>&nbsp;{time_format(waiting_ip.first_date)}&nbsp;</td>
    <td nowrap align=left>&nbsp;{time_format(waiting_ip.last_date)}&nbsp;</td>
    <td nowrap align=center>&nbsp;{waiting_ip.count}&nbsp;</td>
</tr>
</table>
<br />
<table border=0>
<tr>
    <td align="left"><input type="button" class="DialogMainButton" value=" Approve selected " onClick="javascript: document.waiting_ips_form.elements.namedItem('action').value='approve_ip'; document.waiting_ips_form.submit()" /></td>
    <td align="right"><input type="button" value=" Delete selected " onClick="javascript: document.waiting_ips_form.elements.namedItem('action').value='delete_ip'; document.waiting_ips_form.submit()" /></td>
</tr>
</table>
</form>
<br />
{end:}
<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<TR>
    <TD colspan=2>
        <table cellspacing=0 cellpadding=0 border=0 width="100%">
        <tr>
            <td colspan=2>&nbsp;</td>
        </tr>
        <tr>
            <td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Allowed IPs&nbsp;&nbsp;&nbsp;</td>
            <td width=100%>&nbsp;</td>
        </tr>
        <tr>
            <td class="SidebarTitle" align=center colspan=2 height=3></td>
        </tr>
        </table>
    </TD>
</TR>
</table>
{if:allowedList=##}
<div style="width: 320px; text-align: right;">The list is empty</div>
{else:}
<form action="admin.php" method="POST" name="allowed_ips_form">
<input FOREACH="allparams,key,val" type="hidden" name="{key}" value="{val:r}" />
<input type="hidden" name="action" value="" />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="CenterBorder">
<!--begin allowed_ips_list-->
<table border="0" cellspacing="1" cellpadding="2">
<tr class="TableHead">
    <th class="TableHead">IP</th>
    <th class="TableHead">Comment</th>
    <th class="TableHead">
        Delete<br /><input id="allowed_ips" type="checkbox" onClick="javascript: setChecked('allowed_ips_form', 'allowed_ips', this.checked);" />
    </th>
</tr>
<tr FOREACH="allowedList,id,ip" class="{getRowClass(id,#DialogBox#,#TableRow#)}">
    <td align="left">&nbsp;{ip.ip}&nbsp;</td>
    <td align="center">&nbsp;<input type="text" name="comment[{id}]" size="50" maxlength="50" value="{ip.comment}" />&nbsp;</td>
    <td align="center"><input id="allowed_ips" type="checkbox" name="allowed_ips[]" value="{id}" {if:ip.ip=currentIP} disabled{end:}/></td>
</tr>
</table>
<!--end allowed_ips_list-->
    </td>
</tr>
<tr>
    <td><br /></td>
</tr>
<tr>
    <td colspan="7">
        <table border="0" width="100%">
        <tr>
            <td align="left"><input type="button" value=" Update " class="DialogMainButton" onClick="javascript: updateAllowedIPs()" /></td>
            <td align="right"><input type="button" value=" Delete selected " onClick="javascript: if(confirm('Are you sure you want to delete the selected IP addresses?\nAfter the selected IP addresses have been deleted, access to the store from these addresses will be prohibited.\nIf your address is among the selected ones, you will also be prohibited to enter the admin area.')) deleteAllowedIPs()" /></td>
        </tr>
        </table>
    </td>
</tr>
</table>
</form>
{end:}
<br />

<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<TR>                                                                                                                    <TD colspan=2>
        <table cellspacing=0 cellpadding=0 border=0 width="100%">                                                           <tr>
            <td colspan=2>&nbsp;</td>                                                                                       </tr>
        <tr>
            <td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Add new allowed IP&nbsp;&nbsp;&nbsp;</td>
            <td width=100%>&nbsp;</td>
        </tr>
        <tr>
            <td class="SidebarTitle" align=center colspan=2 height=3></td>
        </tr>                                                                                                               </table>
    </TD>                                                                                                           </TR>
</table>
<form action="admin.php" method="POST" name="add_ip_form">
<input FOREACH="allparams,key,val" type="hidden" name="{key}" value="{val:r}" />
<input type="hidden" name="action" value="add_new_ip" />
<table border="0" cellspacing="1" cellpadding="2" class="CenterBorder">
<tr class="TableHead">
    <th colspan="4" class="TableHead">IP</td>
    <th class="TableHead">Comment</td>
</tr>
<tr class="DialogBox">
    <td nowrap="1" width="40" align="left">
        <input type="text" name="byte_1" value="{byte_1}" size="3" maxlength="3" />
    </td>
    <td nowrap="1" width="40" align="left">
        <input type="text" name="byte_2" value="{byte_2}" size="3" maxlength="3" />
    </td>
    <td nowrap="1" width="40" align="left">
        <input type="text" name="byte_3" value="{byte_3}" size="3" maxlength="3" />
    </td>
    <td nowrap="1" width="40" align="left">
        <input type="text" name="byte_4" value="{byte_4}" size="3" maxlength="3" />
    </td>
    <td nowrap="1">
        <input type="text" name="comment" value="{comment}" size="50" maxlength="50" />
    </td>
</tr>
</table>
<table border="0">
<tr class="DialogBox">
    <td nowrap="1" colspan="5">
        <widget class="\XLite\Validator\AdminIPValidator" field="byte_1" field2="byte_2" field3="byte_3" field4="byte_4" />
        {if:ip_error}
            <font class="ValidateErrorMessage">&nbsp;&nbsp;&lt;&lt;&nbsp;Duplicate IP addresses are not allowed</font>
        {end:}
        </td>
</tr>
<tr class="DialogBox">
    <td colspan="5">
        For example: 192.168.0.1, 192.168.*.*, etc.
    </td>
</tr>
<tr>
    <td><br /></td>
</tr>
<tr class="DialogBox">
    <td colspan="5">
        <input type="button" value=" Add " class="DialogMainButton" onClick="javascript: document.add_ip_form.submit()" /></td>
</tr>
</table>
