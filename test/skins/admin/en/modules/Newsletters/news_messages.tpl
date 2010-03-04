<script>
    var selected_ids = new Array();

    function SelectAll(elm)
    {
        for (var i = 0; i < selected_ids.length; i++) {
            var element = document.getElementById(selected_ids[i]);
            if (element) {
                element.checked = elm.checked;
            } 
        } 
    }

</script>

<p align=justify>Use this section to manage existing &quot;{list.name:h}&quot; newsletter messages, resend them or <a href="#send_message"><u>send out new messages</u></a> to newsletter subscribers.</p>

<table cellpadding=3 cellspacing=1 width=90%>
<tr class=TableHead><td>
See also: <br>
<a href="admin.php?target=news_lists"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <b>All newsletters</b></a>
&nbsp;&nbsp;&nbsp;
<a href="admin.php?target=news_subscribers&list_id={list_id}"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <b>&quot;{list.name:h}&quot; subscribers</b></a>
</td></tr>
</table>

<br><br>

<font IF="messages" class="AdminHead">Existing messages</font>
<br><br>

<widget class="XLite_View_Pager" data="{messages}" name="pager">

<table IF="pager.pageData" border=0 cellpadding=5 cellspacing=1 width=90%>
<tr class=TableHead>
    <th><input type="checkbox" onClick="this.blur(); SelectAll(this);"></th>
    <th>Subject</th>
    <th>Time created</th>
    <th>Message</th>
</tr>

<form name="messages_form" action="admin.php" method=POST>
<input FOREACH="allparams,_param,_val" type="hidden" name="{_param}" value="{_val:r}"/>
<input type=hidden name=action value=messages>

<tr FOREACH="pager.pageData,msgid,msg" valign=top>
    <td align=center valign=top>
	    <input id="select_status_{msgid}" type="checkbox" name="ids[]" value="{msg.news_id}" onClick="this.blur();">
	    <script>selected_ids.push("select_status_{msgid}");</script>
    </td>
    <td valign=top><a href="admin.php?target=news_messages&list_id={list_id}&news_id={msg.news_id}">{truncate(msg.subject,32)}</a></td>
    <td valign=top nowrap>{time_format(msg.send_date)}</td>
    <td valign=top>{truncate(msg.body,72)}</td>
</tr>
<tr>
    <td colspan=2 align=left valign=top>
	    <input type=submit name="resend" value=" Resend selected " class="DialogMainButton">
    </td>
	<td colspan=2 align=right valign=top>
        <input type=submit name="delete" value=" Delete selected ">
    </td>
</tr>
<tr><td colspan=4><hr></td></tr>
</form>
</table>
<a name="send_message">&nbsp;</a>

<form action="admin.php#send_message" method=POST>
<input FOREACH="allparams,_param,_val" type="hidden" name="{_param}" value="{_val:r}"/>
<input type=hidden name=action value=send_message>

<table border=0 cellpadding=3 cellspacing=0>
<tr>
    <td colspan=3 class=AdminTitle IF="!news_id">Post new message<br><br></td>
	<td colspan=3 class=AdminTitle IF="news_id">Edit message: #{news_id}<br><br></td>
</tr>
<tr valign=top>
    <td>Subject:</td>
    <td width=8 class=Star>*</td>
    <td width="100%">
        <input type=text name=subject {if:message.subject}value="{message.subject:r}"{else:}value="{subject:r}"{end:} size=80>
        &nbsp;<widget class="XLite_Validator_RequiredValidator" field="subject" visible="{!test}">
    </td>
</tr>
<tr valign=top>
    <td nowrap>Message text:</td>
    <td>&nbsp;</td>
    <td width="100%">
        <textarea name=body rows=12 cols=80>{if:message.subject}{message.body:r}{else:}{body:r}{end:}</textarea>
    </td>
</tr>
<tr valign=top>
    <td>Test e-mail:</td>
    <td>&nbsp;</td>
    <td width="100%">
        <input type=text name="subscribers[]" value="{subscribers.0:r}" size=52 maxlength=128>
        &nbsp;&nbsp;&nbsp;
		<input type=submit name="postonly" value="Post and test message">
    </td>
</tr>
<tr>
    <td colspan=2>&nbsp;</td>
    <td colspan=2>
		<input type=submit value=" Post and send out " class="DialogMainButton">
    </td>
</tr>
</table>
</form>

