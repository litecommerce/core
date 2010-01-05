<p align=justify>Use this section to manage your store's promotional newsletters and customer announcements, modify lists of subscribers and send out newsletter messages. Each newsletter has its own list of subscribers and message history.</p>

<br clear="all">

<table IF="newsLists" border=0 cellspacing=0 cellpadding=3 width=90%>
<tr>
    <td colspan=3 class=AdminHead>Available newsletters<br><br></td>
</tr>
<form FOREACH="newsLists,nsid,nl" action="admin.php" method=POST id="list_form_{nl.list_id}">
<input type=hidden name=target value=news_lists>
<input type=hidden name=action value=update_list>
<input type=hidden name=list_id value="{nl.list_id}">

<tr><td colspan="3">
	<table border="0" cellspacing="1" cellpadding="5" width=100%>
	<tr>
		<td colspan="3" class="ErrorMessage" IF="errorDesc=#newsExists#&nl.list_id=list_id">A newsletter titled ({name:h}) already exists.</td>
	</tr>
	<tr class="TableHead">
		<th colspan="3" align="left"> Newsletter #{inc(nsid)}:
			<input type=text name="name" size="28" maxlength="128" value="{nl.name:h}">
			&nbsp;&nbsp;
			<input type=button value=" Post new message... " onclick="document.location='admin.php?target=news_messages&list_id={nl.list_id}#send_message'">
			<span IF="nl.enabled=#0#" class="ErrorMessage">
			&nbsp;&nbsp;&nbsp;&nbsp;
			(inactive)
			</span>
		</th>
    </tr>
	<tr class="TableRow">
		<th width="40%">Description</th>
	    <th>Active</th>
        <th>Messages: {count(nl.messages)}</th>
	</tr>
	<tr>
	<td rowspan="3"><textarea name="description" rows="5" cols="28">{nl.description:h}</textarea></td>
	<td align="center" valign="top">
        <select name="enabled">
		    <option value=0 selected="nl.enabled=#0#">No</option>
			<option value=1 selected="nl.enabled=#1#">Yes</option>
        </select>
    </td>
	<td align="center" valign="top">
	    <input type=button value=" Edit messages " onclick="document.location='admin.php?target=news_messages&list_id={nl.list_id}'"></td>
	</tr>
    <tr class="TableRow">
        <th>In the news</th>
	    <th>Subscribers: {count(nl.subscribers)}</th>
    </tr>
	<tr>
	<td align="center" valign="top">
	    <select name="show_as_news">
	        <option value=0 selected="nl.show_as_news=#0#">No</option>
	        <option value=1 selected="nl.show_as_news=#1#">Yes</option>
	    </select>
    </td>
    <td align="center" valign="top">
	    <input type=button value=" Edit subscribers " onclick="document.location='admin.php?target=news_subscribers&list_id={nl.list_id}'"></td>
	</tr>
	<tr>
	<td align="left"><input type=submit name=update value=" Update " class="DialogMainButton"></td>
	<td colspan="2" align="right">
	<script language="JavaScript">
    <!-- 
    function confirm_delete(list_id)
        {
            if (confirm("Are you sure you want to delete news list?\n\n"+"All subscription and messages information will be lost!")) {
                list_form = document.getElementById("list_form_" + list_id);
                if (list_form != null) {
                    list_form.action.value = "delete_list";
                    list_form.submit();
                }
            }
       }
       // -->
    </script>
    <input type=button name=delete value=" Delete " onclick="confirm_delete({nl.list_id})">
    </td>
    </tr>
	<tr><td colspan=3><hr></td></tr>
</table>
</td></tr>
</form>
</table>

<span IF="newsLists&!xlite.factory.NewsList.showedListsNumber">
<font class="Star">(*) No newsletters are currently available in the news headlines box at the Customer Zone.</font>
</span>

<p>
<a name=add_list></a>

<table border=0 cellspacing=0 cellpadding=3 width=90%>
<form action="admin.php#add_list" method=POST>
<input type=hidden name=target value=news_lists>
<input type=hidden name=action value=add_list>
<tr>
    <td class="AdminTitle">Add a newsletter<br><br></td>
</tr>
<tr>
    <td>
	    <table border="0" cellspacing="1" cellpadding="5" width=100%>
        <tr class="TableHead">
            <th colspan="3" align="left"> Newsletter title <font class="Star">*</font>
			    <input type=text name="name" size=28 maxlength=128 value="{name:h}">
				&nbsp;&nbsp;
			    <widget class="XLite_Module_Newsletters_Validator_NewsListValidator" field="name">
            </th>
        </tr>
        <tr class="TableRow">
            <th>Description</th>
            <th>Active</th>
        </tr>
		<tr>
		   <td rowspan="3"><textarea name="description" rows="5" cols="45">{description:h}</textarea></td>
           <td align="center" valign="top">
	           <select name="enabled">
                   <option value=0 selected="enabled=0">No</option>
                   <option value=1 selected="enabled=1">Yes</option>
               </select>
           </td>
        </tr>
		<tr class="TableRow">
	        <th>In the news</th>
	    </tr>
        <tr>
	        <td align="center" valign="top">
	            <select name="show_as_news">
	                <option value=0 selected="show_as_news=0">No</option>
	                <option value=1 selected="show_as_news=1">Yes</option>
	            </select>
            </td>
        </tr>
		<tr>
		    <td colspan="2"><input type=submit name=add value=" Add "></td>
        </tr>
	</table>
    </form>
</table>
