<script>
	var selected = false;
	var child_ids = new Array();
	var email_admin = new Array();
	var email_customer = new Array();
	var keys = new Array();
	var index = 0;

	function SelectAll()
	{
	    selected = !selected;
		setChecked("update_status_form","active_status",selected);
	}

	function setEmailChecked(elm, type)
	{
		var ids = (type != 0) ? email_admin : email_customer;
	    for (var i = 0; i < ids.length; i++) {
			var element = document.getElementById(ids[i]);
			if (element) {
   		    	element.checked = elm.checked;
   		    }
		}
		return true;
	}

	function preSetEmail(type)
	{
		var elm = (type != 0) ? "admin_email_action" : "customer_email_action";
		var element = document.getElementById(elm);
		if (element) {
   	    	element.checked = true;
   	    }
		return true;
	}

	function setChecked(form, input, check)
	{
	    for (var i = 0; i < child_ids.length; i++) {
			var element = document.getElementById(child_ids[i]);
			if (element) {
   		    	element.checked = check;
   		    }
		}
		for (var i = 0; i < keys.length; i++) {
			var element = document.getElementById("open_" + keys[i]);
			if (element) {
				if (element.style.display == "none") {
					VisibleBox(keys[i]);
				}
			}
		}
		return true;
	}

	function VisibleBox(key)
	{
        var open = document.getElementById("open_" + key);
        var close = document.getElementById("close_" + key);
		var child_key 		= document.getElementById("child_" + key + "_key");
		var child_name		= document.getElementById("child_" + key + "_name");
		var child_notes 	= document.getElementById("child_" + key + "_notes");
		var child_orderby 	= document.getElementById("child_" + key + "_orderby");
		var child_status	= document.getElementById("child_" + key + "_status");
		var child_email    = document.getElementById("child_" + key + "_email");
		var child_cemail    = document.getElementById("child_" + key + "_cust_email");

 
        if (open.style.display == "") {
            open.style.display = "none";
            close.style.display = "";
            child_key.style.display = "none";
            child_name.style.display = "none";
            child_notes.style.display = "none";
            child_orderby.style.display = "none";
            child_status.style.display = "none";
            child_email.style.display = "none";
            child_cemail.style.display = "none";
        } else {
            open.style.display = "";
            close.style.display = "none";
			child_key.style.display = "";
            child_name.style.display = "";
            child_notes.style.display = "";
            child_orderby.style.display = "";
            child_status.style.display = "";
			child_email.style.display = "";
            child_cemail.style.display = "";
        }
	}

	function deleteStatus()
	{
	    if (confirm('You are about to delete selected statuses.\n\nAre you sure you want to delete them?')) { 
    	    document.update_status_form.action.value = 'delete'; 
       		document.update_status_form.submit(); 
        	return true;
	    }
    	return false;
	}	

	function checkName()
	{
		var status_name = document.getElementById("add_status_name");	
		if (status_name.value == "") {
			alert("Status name is empty");
			return false;
		}
        document.add_status_form.submit(); 
		return true;
	}
</script>
<form action="admin.php" method="POST" name="update_status_form">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="update">
<table width="80%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="CenterBorder" colspan="2">
            <table width="100%" cellspacing="1" cellpadding="0" border="0">
                <tr class="TableHead">
                    <th colspan="2" rowspan="2" valign=top>Status Name</th>
                    <th rowspan="2" valign=top>Notes</th>
					<th width="8%" rowspan="2" valign=top>Pos.</th>
					<th width="8%" colspan="2" valign=top>E-mail</th>
					<th width="8%" rowspan="2" valign=top>Delete<br><br><input type="checkbox" onClick="this.blur(); SelectAll();"></th>
                </tr>
                <tr class="TableHead">
                    <th width="8%">Admin<br><input type="checkbox" id="admin_email_action" onClick="this.blur(); setEmailChecked(this,1);"></th>
                    <th width="8%">Customer<br><input type="checkbox" id="customer_email_action" onClick="this.blur(); setEmailChecked(this,0);"></th>
                </tr>
				<tr FOREACH="orderStatuses,key,status" class="DialogBox">
					<td valign="top">
						<table width="100%" cellpadding="3" cellspacing="0">
							<tr valign="top">
								<td id="open_{key}">{if:status.children}<img src="images/minus.gif" border="0" onClick="javascript: VisibleBox('{key}');">{end:}</td>
								<td id="close_{key}" style="display: none;">{if:status.children}<img src="images/plus.gif" border="0" onClick="javascript: VisibleBox('{key}');">{end:}</td>
								<td align="right" height="54">{key}</td>
                            </tr>
							<script>
								keys[index++] = "{key}";
							</script>
							<tbody id="child_{status.base.status}_key">
							<tr class="TableHead" FOREACH="status.children,key,child" valign="top">
								<td>&nbsp;</td>
								<td align="right" height="54">{if:child.disabled}<font class="Star">*</font>{end:}{if:!isEmpty(key)}{key}{else:}?{end:}</td>
							</tr>
							</tbody>
						</table>
					</td>
	                <td valign="top">
						<table width="100%" cellpadding="3" cellspacing="0">
                            <tr>
                                <td height="54" valign="top"><input type="text" name="update_status[{status.base.status_id}][name]" class="Input" value="{status.base.name:h}" style="width:140px;"></td>
                            </tr>
							<tbody id="child_{status.base.status}_name">
                            <tr class="TableHead" FOREACH="status.children,key,child">
                                <td height="54" valign="top"><input type="text" name="update_status[{child.status.status_id}][name]" class="Input" value="{child.status.name:h}" style="width:140px;"></td>
                            </tr>
							</tbody>
                        </table>
					</td>
	                <td valign="top">
                        <table width="100%" cellpadding="3" cellspacing="0">
                            <tr>
                                <td height="54" valign="top"><textarea name="update_status[{status.base.status_id}][notes]" rows="3" cols="25" class="Input" style="height:45px;width:180px;">{status.base.notes:h}</textarea></td>
                            </tr>
							<tbody id="child_{status.base.status}_notes">
                            <tr class="TableHead" FOREACH="status.children,key,child">
                                <td height="54" valign="top"><textarea name="update_status[{child.status.status_id}][notes]" class="Input" cols="25" rows="3" style="height:45px;width:180px;">{child.status.notes:h}</textarea></td>
                            </tr>
							</tbody>
                        </table>
					</td>
	                <td valign="top">
                        <table width="100%" cellpadding="3" cellspacing="0">
                            <tr valign="top" height="54">
                                <td><input type="text" name="update_status[{status.base.status_id}][orderby]" class="Input" value="{status.base.orderby:h}"></td>
                            </tr>
							<tbody id="child_{status.base.status}_orderby">
                            <tr class="TableHead" FOREACH="status.children,key,child">
                                <td valign="top" height="54"><input type="text"  name="update_status[{child.status.status_id}][orderby]" class="Input" value="{child.status.orderby}"></td>
                            </tr>
							</tbody>
                        </table>
					</td>
                    <td valign="top">
                        <table width="100%" cellpadding="3" cellspacing="0">
                            <tr>
                                <td align="center" valign="top" height="54"><input type="checkbox" name="update_status[{status.base.status_id}][email]" id="email_admin_{status.base.status_id}" {if:status.base.email}checked{end:} onCLick="this.blur();"></td>
    							<script>
    								email_admin.push("email_admin_{status.base.status_id}");
    								{if:status.base.email}
    								preSetEmail(1);
    								{end:}
    							</script>
                            </tr>
                            <tbody id="child_{status.base.status}_email">
                            <tr class="TableHead" FOREACH="status.children,key,child">
                                <td valign="top" height="54" align="center"><input type="checkbox" name="update_status[{child.status.status_id}][email]" id="email_admin_{child.status.status_id}" {if:child.status.email}checked{end:} onCLick="this.blur();"></td>
    							<script>
    								email_admin.push("email_admin_{child.status.status_id}");
    								{if:child.status.email}
    								preSetEmail(1);
    								{end:}
    							</script>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                    <td valign="top">
                        <table width="100%" cellpadding="3" cellspacing="0">
                            <tr>
                                <td align="center" valign="top" height="54"><input type="checkbox" name="update_status[{status.base.status_id}][cust_email]" id="email_customer_{status.base.status_id}" {if:status.base.cust_email}checked{end:} onCLick="this.blur();"></td>
    							<script>
    								email_customer.push("email_customer_{status.base.status_id}");
    								{if:status.base.cust_email}
    								preSetEmail(0);
    								{end:}
    							</script>
                            </tr>
                            <tbody id="child_{status.base.status}_cust_email">
                            <tr class="TableHead" FOREACH="status.children,key,child">
                                <td valign="top" height="54" align="center"><input type="checkbox" name="update_status[{child.status.status_id}][cust_email]" id="email_customer_{child.status.status_id}" {if:child.status.cust_email}checked{end:} onCLick="this.blur();"></td>
    							<script>
    								email_customer.push("email_customer_{child.status.status_id}");
    								{if:child.status.cust_email}
    								preSetEmail(0);
    								{end:}
    							</script>
                            </tr>
                            </tbody>
                        </table>
                    </td>   
                    <td valign="top">
						<table width="100%" cellpadding="3" cellspacing="0" border="0">
                            <tr align="center">
                                <td valign="top" height="54">&nbsp;</td>
                            </tr>
							<tbody id="child_{status.base.status}_status">
                            <tr class="TableHead" align="center" FOREACH="status.children,key,child">
                                <td valign="top" height="54"><input id="active_status_{child.status.status_id}" type="checkbox" name="delete_status[]" value="{child.status.status_id}" {if:child.disabled}disabled {end:} onCLick="this.blur();"></td>
							<script>
								child_ids.push("active_status_{child.status.status_id}");
							</script>
							</tr>
							</tbody>
                        </table>
                    </td>
	    		</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="submit" value=" Update "></td>
		<td align="right"><input type="button" value=" Delete selected " onclick="javascript: deleteStatus();"></td>
	</tr>
    <tr>
        <td colspan="2" align="right" ><br></td>
	</tr>
	<tr IF="deleted">
		<td colspan="2" align="right" ><b>Note:</b> Order statuses marked with an asterisk (<font class="Star">*</font>) cannot be deleted<br>because they are being assigned to some order(s).</td>
	</tr>
</table>
</form>									
<br>
<form action="admin.php" method="POST" name="add_status_form">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="add_status">
<table width="80%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
		<font class="AdminTitle">Add new status</font>
		</td>
	</tr>
    <tr>
        <td>&nbsp;</td>
	</tr>
    <tr IF="isEmpty(letters)">
        <td><b>Warning:</b> The list of unassigned order status codes is empty. You need to delete one or more order statuses.</td>
	</tr>
    <tr IF="!isEmpty(letters)">
        <td class="CenterBorder">
			<table width="100%" cellspacing="1" cellpadding="3" border="0">		
				<tr class="TableHead">
					<th width="8%">Code</th>
					<th width="30%">Status name</th>
					<th>Parent</th>
					<th width="40%">Notes</th>
					<th width="8%">Pos.</th>
				</tr>
				<tr class="DialogBox" valign="top">
					<td align="center"><select name="add_status[status]">
						<option FOREACH="letters,key,letter" value="{letter}">{letter}</option>
						</select>
					</td>
			        <td><input class="Input" type="text" id="add_status_name" name="add_status[name]" value="" size="37" style="width:140px;"></td>
			        <td><select name="add_status[parent]" style="width:100px;">
			                <option FOREACH="parentStatuses,status" value="{status.status}">{status.name}</option>
						</select>
			        </td>
			        <td><textarea class="Input" name="add_status[notes]" style="width:180px;"></textarea></td>
			        <td><input class="Input" type="text" name="add_status[orderby]" value=""></td>
				</tr>	
			</table>
		</td>
	</tr>
</table>
<br>
<table width="80%" cellpadding="0" cellspacing="0" IF="!isEmpty(letters)">
	<tr class="DialogBox">
		<td align="right"><input type="button" value = ' Add ' onClick="javascript: checkName();"></td>
    </tr>
</table>
</form>
