<p align=justify>Use this section to manage the list of &quot;{list.name:h}&quot; newsletter subscribers.<br>
You can <a href="#add_subscriber"><u>add new subscribers manually</u></a> or <a href="#import_subscriber"><u>import a list of subscribers</u></a> from a file.</p>

<table cellpadding=3 cellspacing=1 width=90%>
<tr class=TableHead><td>
See also: <br>
<a href="admin.php?target=news_lists"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <b>All newsletters</b></a>
&nbsp;&nbsp;&nbsp;
<a href="admin.php?target=news_messages&list_id={list_id}"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <b>&quot;{list.name:h}&quot; messages</b></a>
&nbsp;&nbsp;&nbsp;
<a href="admin.php?target=news_messages&list_id={list_id}#send_message"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <b>Post new message</b></a>
</td></tr>
</table>

<br> 

<table cellpadding=3 cellspacing=1 width=90%>
<form action="admin.php" method="GET" name="subscriber_search_form">
<span FOREACH="dialog.allparams,name,val">
<span IF="!name=#subscriber#">
<input type="hidden" name="{name}" value="{val}">
</span>
</span>
<tr>
	<td><font class="AdminHead">Subscribers</font></td>
	<td noWrap height=10>&nbsp;</td>
	<td align=right><input type="text" name="subscriber" size=20 value="{subscriber:r}"></td>
	<td align=left><input type=submit name="Search" value="Filter"></td>
</tr>
<tr IF="!subscribers">
	<td colspan=4><font class="Star">No subscribers found for this newsletter</font></td>
</tr>
<tr>
	<td colspan=4>
	<widget class="CPager" data="{subscribers}" name="pager" itemsPerPage="{config.Newsletters.subscribers_per_page_admin}">
	</td>
</tr>
</form>
</table>


<table IF="pager.pageData" border=0 cellpadding=3 cellspacing=1 width=90%>

<script language="Javascript">
	var subscribers = new Array(); 

	function SelectAll(elm)
	{
    	for (var i = 0; i<subscribers.length; i++) {
    		var element = document.getElementById(subscribers[i]);
    		if (element) {
        		element.checked = elm.checked;
        	}
	    }
	}

function SortBy(field)
{
	document.subscribers_sort_form.sortby.value = field;
    document.subscribers_sort_form.submit();
}
</script>

<form action="admin.php" method="GET" name="subscribers_sort_form">
<span FOREACH="dialog.allparams,name,val">
<span IF="!name=#sortby#">
<input type="hidden" name="{name}" value="{val}">
</span>
</span>
<input type=hidden name=sortby value="{sortby}">
<tr class=TableHead>
    <th><input type="checkbox" onClick="this.blur(); SelectAll(this);"></th>
    <td><a href="javascript: SortBy('email')"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> E-mail</a></td>
    <td><a href="javascript: SortBy('since_date')"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Time subscribed</a></td>
</tr>
</form>

<form action="admin.php" method=POST>
<input type=hidden name=target value=news_subscribers>
<input type=hidden name=action value=unsubscribe>
<input type=hidden name=list_id value="{list_id}">

<tr FOREACH="pager.pageData,sbid,sub">
    <td align=center valign=top>
	    <input id="subscriber_{sbid}" type="checkbox" name="emails[{sub.email:h}]" value="1" onClick="this.blur();">
		        <script>subscribers.push("subscriber_{sbid}");</script>
    </td>
    <td>{sub.email:h}</td>
    <td>{time_format(sub.since_date)}</td>
</tr>
<tr>
    <td colspan=3 align=left>
	    <input type=submit value=" Unsubscribe selected ">
		<hr>
    </td>
</tr>
</form>
</table>

<a name="add_subscriber">&nbsp;</a><br>

<form action="admin.php#add_subscriber" method=POST>
<input type=hidden name=target value=news_subscribers>
<input type=hidden name=action value=add_subscriber>
<input type=hidden name=list_id value="{list_id}">

<p IF="userExists" class="ErrorMessage">&gt;&gt; This e-mail is already subscribed to this newsletter, please specify another e-mail &lt;&lt;</p>

<table border=0 cellpadding=3 cellspacing=0 width=90%>
<tr>
    <td colspan=3 class=AdminTitle>Add subscriber<br><br></td>
</tr>
<tr>
    <td nowrap>E-mail:</td>
    <td class=Star>*</td>
    <td width=90%>
        <input type=text name=email value="{email:r}" size=35>
        &nbsp;<widget class="CEmailValidator" field="email">
    </td>
</tr>
<tr>
    <td colspan=3><input type=submit value=" Add "></td>
</tr>
</table>
</form>

<a name="import_subscriber"></a>

<br>
<form action="admin.php" method=POST enctype="multipart/form-data">
<input type=hidden name=target value=news_subscribers>
<input type=hidden name=action value=import_subscribers>
<input type=hidden name=list_id value="{list_id}">

<table border=0 cellpadding=3 cellspacing=0 width=90%>
<tr>
    <td colspan=3 class=AdminTitle>Import subscribers</td>
</tr>
<tr>
    <td colspan=3>
    <p>File to be imported must be a text file containing e-mail addresses of the subscribers, one per line.<br>Duplicate e-mail address entries will be ignored.<br><br></p>
	</td>
</tr>
<tr>
    <td>File:</td>
    <td class=Star>*</td>
    <td width="90%">
        <input type=file name=userfile>
    </td>
</tr>
<tr>
    <td colspan=3><input type=submit value=" Import "></td>
</tr>
</table>
</form>
