{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Customer notifications management page template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<span IF="!getRequestParamValue(#mode#)=#process#">

  <span align=justify>This section allows you to manage customer notifications.</span>

<script language="Javascript">
<!--

function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

function ClearAllFilters()
{
	var filters = new Array("search_email", "search_pinfo", "search_type", "search_status", "search_period", "search_prodname");
    var Element;
    for (var i=0; i<filters.length; i++) {
    	Element = document.getElementById(filters[i]);
    	if (Element) {
    		switch (filters[i]) {
    			case "search_email":
    			case "search_pinfo":
    			case "search_prodname":
    				Element.value = "";
    			break;
    			case "search_type":
					Element.checked = true;
    			break;
    			case "search_status":
					Element.checked = true;
    			break;
    			case "search_period":
    				Element.selectedIndex = 0;
    			break;
    		}
        }
    }

    SetPeriod();
}

function SetDateElements(dateVal,prefix,custom)
{
	var elm1 = document.getElementById("search_"+prefix+"Year");
	var elm2 = document.getElementById("search_"+prefix+"Month");
	var elm3 = document.getElementById("search_"+prefix+"Day");
	var elm4 = document.getElementById("search_"+prefix+"Raw");
	if (!(elm1 && elm2 && elm3 && elm4))
		return;

    if (!custom) {
    	for(var i=0; i<elm1.length; i++) {
    		if (elm1.options[i] == dateVal.getYear()) {
    			elm1.selectedIndex = i;
    			break;
    		}
    	}
    }
	elm1.disabled = (!custom) ? true : false;

    if (!custom) {
		elm2.selectedIndex = dateVal.getMonth();
	}
	elm2.disabled = (!custom) ? true : false;
    if (!custom) {
		elm3.selectedIndex = (dateVal.getDate() - 1);
	}
	elm3.disabled = (!custom) ? true : false;
    if (!custom) {
    	var dateTime = new String;
        dateTime += dateVal.getTime();
    	elm4.value = dateTime.substr(0,10);
    }
}

function SetMonthPeriod(previous)
{
    var currentDate = new Date();
    var startDate = new Date(currentDate.getYear(),currentDate.getMonth(),currentDate.getDate());

    previous = (previous) ? 1 : 0;
    startDate.setMonth(currentDate.getMonth()-previous);
    startDate.setDate(1);
    SetDateElements(startDate, "startDate");

    var endDate = new Date(startDate.getYear(),startDate.getMonth()+1,1);
    endDate.setDate(endDate.getDate()-1);
    SetDateElements(endDate, "endDate");
}

function SetWeekPeriod(previous)
{
    var currentDate = new Date();
    var startDate = new Date(currentDate.getYear(),currentDate.getMonth(),currentDate.getDate());

    var currentDay = currentDate.getDay();
    if (currentDay == 0) {
    	currentDay = 7;
    }

    previous = (previous) ? 7 : 0;
    startDate.setDate(currentDate.getDate()-currentDay+1-previous);
    SetDateElements(startDate, "startDate");

    var endDate = new Date(startDate.getYear(),startDate.getMonth(),startDate.getDate());
    endDate.setDate(startDate.getDate()+6);
    SetDateElements(endDate, "endDate");
}

function SetDayPeriod(previous)
{
    var currentDate = new Date();
    var startDate = new Date(currentDate.getYear(),currentDate.getMonth(),currentDate.getDate());

    previous = (previous) ? 1 : 0;
    startDate.setDate(currentDate.getDate()-previous);
    SetDateElements(startDate, "startDate");

    var endDate = new Date(startDate.getYear(),startDate.getMonth(),startDate.getDate());
    SetDateElements(endDate, "endDate");
}

function SetCustomPeriod()
{
    SetDateElements(null, "startDate", true);
    SetDateElements(null, "endDate", true);
}

function SetPeriod()
{
	var Element = document.getElementById("search_period");
	if (!Element)
		return;

	var period = Element.options[Element.selectedIndex].value;
	switch (period) {
		case "-1":
		case "0":
			SetDayPeriod();
		break;
		case "1":
			SetDayPeriod(true);
		break;
		case "2":
			SetWeekPeriod();
		break;
		case "3":
			SetWeekPeriod(true);
		break;
		case "4":
			SetMonthPeriod();
		break;
		case "5":
			SetMonthPeriod(true);
		break;
		case "6":
			SetCustomPeriod();
		break;
	}
	visibleBox("custom_dates", ((period == "6")?true:false));
}

function SaveFilters()
{
	document.subscriber_search_form.action.value = "save_filters";
	document.subscriber_search_form.submit();
}

-->
</script>

  <form action="admin.php" method="get" name="subscriber_search_form">

    <span FOREACH="getAllParams(#email,pinfo,type,status,period,startDateRaw,endDateRaw,startDateDay,startDateMonth,startDateYear,endDateDay,endDateMonth,endDateYear#),name,val">
      <input type="hidden" name="{name}" value="{val}">
    </span>
    <input type="hidden" name="action" value="">

    <table width="100%" border=0>

      <tr>
        <td width="100%">

          <table cellspacing=0 cellpadding=0 border=0 width="100%">

          	<tr IF="!id=#0#">
          		<td colspan=2>&nbsp;</td>
            </tr>

          	<tr>
          		<td class="SidebarTitle" align=center width=150 nowrap>Search filters</td>
          		<td width=100%>&nbsp;</td>
            </tr>

          	<tr>
          		<td class="SidebarTitle" align=center colspan=2 height=3></td>
          	</tr>

          </table>

        </td>
      </tr>

      <tr>
        <td>

          <table cellspacing=0 cellpadding=0 border=0>

           	<tr>
            	<td rowspan=3>&nbsp;&nbsp;&nbsp;</td>
            	<td nowrap valign=top>Type:&nbsp;</td>
              <td valign=top>

                <table cellspacing=0 cellpadding=0 border=0>

                  <tr>
                		<td>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=radio name="type" id="search_type" checked="{getFilterParam(#type#)=##|getFilterParam(#type#)=#A#}" value="A" onClick="this.blur()"></td>
                    		<td>All</td>
                    	</tr>
                    	</table>
            		</td>
            	</tr>
            	<tr>
            		<td>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=radio name="type" checked="{isSelected(getFilterParam(#type#),#product#)}" value="product" onClick="this.blur()"></td>
                    		<td><img src="images/modules/ProductAdviser/product.gif" width=14 height=14 border=0></td>
							<td>&nbsp;</td>
                    		<td>Product</td>
                    	</tr>
                    	</table>
            		</td>
            	</tr>
            	<tr>
            		<td>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=radio name="type" checked="{isSelected(getFilterParam(#type#),#price#)}" value="price" onClick="this.blur()"></td>
                    		<td><img src="images/modules/ProductAdviser/price.gif" width=14 height=18 border=0></td>
							<td>&nbsp;</td>
                    		<td>Price</td>
                    	</tr>
                    	</table>
            		</td>
            	</tr>
            	</table>
        	</td>
        	<td>&nbsp;&nbsp;</td>
			<td style="background:#E8E8E8" width=2></td>
			<td>&nbsp;&nbsp;</td>
        	<td nowrap valign=top>Status:&nbsp;</td>
        	<td valign=top colspan=2>
            	<table cellspacing=0 cellpadding=0 border=0>
            	<tr>
            		<td colspan=3>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=radio name="status" id="search_status" checked="{getFilterParam(#status#)=##|getFilterParam(#status#)=#A#}" value="A" onClick="this.blur()"></td>
                    		<td>All</td>
                    	</tr>
                    	</table>
            		</td>
            	</tr>
            	<tr>
            		<td>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=radio name="status" checked="{isSelected(getFilterParam(#status#),#Q#)}" value="Q" onClick="this.blur()"></td>
                    		<td><img src="images/modules/ProductAdviser/queued.gif" width=12 height=11 border=0></td>
							<td>&nbsp;</td>
                    		<td>Waiting</td>
                    	</tr>
                    	</table>
            		</td>
					<td>&nbsp;&nbsp;</td>
            		<td>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=radio name="status" checked="{isSelected(getFilterParam(#status#),#S#)}" value="S" onClick="this.blur()"></td>
                    		<td><img src="images/modules/ProductAdviser/sent.gif" width=12 height=11 border=0></td>
							<td>&nbsp;</td>
                    		<td>Sent</td>
                    	</tr>
                    	</table>
            		</td>
            	</tr>
            	<tr>
            		<td>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=radio name="status" checked="{isSelected(getFilterParam(#status#),#U#)}" value="U" onClick="this.blur()"></td>
                    		<td><img src="images/modules/ProductAdviser/updated.gif" width=12 height=11 border=0></td>
							<td>&nbsp;</td>
                    		<td>Ready</td>
                    	</tr>
                    	</table>
            		</td>
					<td>&nbsp;&nbsp;</td>
            		<td>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=radio name="status" checked="{isSelected(getFilterParam(#status#),#D#)}" value="D" onClick="this.blur()"></td>
                    		<td><img src="images/modules/ProductAdviser/declined.gif" width=12 height=11 border=0></td>
							<td>&nbsp;</td>
                    		<td>Declined</td>
                    	</tr>
                    	</table>
            		</td>
            	</tr>
            	</table>
        	</td>
        	<td>&nbsp;&nbsp;</td>
			<td style="background:#E8E8E8" width=2></td>
			<td>&nbsp;&nbsp;</td>
        	<td valign=top>
            	<table cellspacing=0 cellpadding=0 border=0>
            	<tr>
                	<td nowrap>E-mail:</td>
                	<td>&nbsp;</td>
                	<td><input type="text" name="email" id="search_email" value="{getFilterParam(#email#):r}"></td>
            	</tr>
            	<tr>
                	<td nowrap>Person info:</td>
                	<td>&nbsp;</td>
        			<td><input type="text" name="pinfo" id="search_pinfo" value="{getFilterParam(#pinfo#):r}"></td>
            	</tr>
            	<tr>
                	<td nowrap>Product name:</td>
                	<td>&nbsp;</td>
        			<td><input type="text" name="prodname" id="search_prodname" value="{getFilterParam(#prodname#):r}"></td>
            	</tr>
            	</table>
        	</td>
    	</tr>
        <tr>
        	<td colspan=20><hr></td>
        </tr>
    	<tr>
        	<td valign=top colspan=6>
            	<table cellspacing=0 cellpadding=0 border=0>
            	<tr>
                	<td nowrap>Search period:</td>
                	<td>&nbsp;</td>
                	<td>
                	<select name="period" id="search_period" onChange="SetPeriod()">
                	<option value="-1" selected="{isSelected(getFilterParam(#period#),#-1#)}">Whole period</option>
                	<option value="0" selected="{isSelected(getFilterParam(#period#),#0#)}">Today</option>
                	<option value="1" selected="{isSelected(getFilterParam(#period#),#1#)}">Yesterday</option>
                	<option value="2" selected="{isSelected(getFilterParam(#period#),#2#)}">Current week</option>
                	<option value="3" selected="{isSelected(getFilterParam(#period#),#3#)}">Previous week</option>
                	<option value="4" selected="{isSelected(getFilterParam(#period#),#4#)}">Current month</option>
                	<option value="5" selected="{isSelected(getFilterParam(#period#),#5#)}">Previous month</option>
                	<option value="6" selected="{isSelected(getFilterParam(#period#),#6#)}">Custom period</option>
                	</select>
                	</td>
            	</tr>
            	<tbody id="custom_dates" style="display:none">
            	<tr>
                	<td nowrap>Date from:</td>
                	<td>&nbsp;</td>
                	<td nowrap><widget class="\XLite\View\Date" field="startDate" value="{getDateValue(#start#)}" id_prefix="search_"></td>
            	</tr>
            	<tr>
                	<td nowrap>Date through:</td>
                	<td>&nbsp;</td>
                	<td nowrap><widget class="\XLite\View\Date" field="endDate" value="{getDateValue(#end#)}" id_prefix="search_"></td>
            	</tr>
            	</tbody>
            	</table>
        	</td>
        	<td valign=middle colspan=9>
            	<table cellspacing=0 cellpadding=0 border=0 width=100% valign=middle>
            	<tr>
                	<td>&nbsp;&nbsp;&nbsp;</td>
                	<td nowrap>
                	<a href="javascript: ClearAllFilters()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle" IF="sortby=#type#"> Clear all filters</a>
                	</td>
                	<td>&nbsp;&nbsp;&nbsp;</td>
                	<td nowrap>
                	<a href="javascript: SaveFilters()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle" IF="sortby=#type#"> Save filter preferences</a>
                	</td>
                	<td>&nbsp;&nbsp;&nbsp;</td>
                	<td width=100% align=right><input type=submit value="Search"></td>
            	</tr>
            	</table>
        	</td>
    	</tr>
        <tr>
        	<td align=left colspan=20>
        	<BR>
        	<span IF="!notifications">No subscribers found.</span>
        	<span IF="notifications"><b>{notificationsNumber}</b> record<span IF="!notificationsNumber=#1#">s</span> found.</span>
        	</td>
        </tr>
		<script language="Javascript">SetPeriod()</script>
    	</table>
    </td>
</tr>
</form>
</table>

<br>

<widget class="\XLite\View\PagerOrig" data="{notifications}" name="pager" itemsPerPage="{xlite.config.CDev.ProductAdviser.number_notifications}">

<table IF="namedWidgets.pager.pageData" border=0 cellpadding=1 cellspacing=3>

<script language="Javascript">
<!--

var notifications = new Array(); 
var statuses = new Array(); 

var canBeSent = 0;
var canBeDeclined = 0;
var canBeDeleted = 0;

function ProcessAll(status)
{
    for (var i=0; i<notifications.length; i++) {
    	var Element = document.getElementById("notification_"+notifications[i]);
    	if (Element) {
    		if (Element.checked != status) {
        		Element.checked = status;
            	CheckBoxHandler(notifications[i]);
            }
        }
    }
}

function CheckAll()
{
	ProcessAll(true);
}

function UncheckAll()
{
	ProcessAll(false);
}

function CheckBoxAction(elm)
{
	if (elm.checked)
	{
		CheckAll();
	}
	else
	{
		UncheckAll();
	}
}

function SortBy(field)
{
	document.notifications_sort_form.sortby.value = field;
    document.notifications_sort_form.submit();
}

function ButtonStatus(button,status,value)
{
	var Element = document.getElementById(button);
	if (Element) {
		var disabled = (status > 0) ? false : true;
        Element.disabled = disabled;
        Element.value = " " + value + " " + ((status > 0) ? "(" + status + ")" : "");
	}
}

function CheckBoxHandler(ntf_id)
{
	var checkBox = document.getElementById("notification_"+ntf_id);
	if (checkBox) {
		switch (statuses[ntf_id]) {
			case "Q":
				canBeDeclined += (checkBox.checked) ? 1 : -1;
			break;
			case "U":
				canBeSent += (checkBox.checked) ? 1 : -1;
				canBeDeclined += (checkBox.checked) ? 1 : -1;
			break;
			case "S":
				canBeDeleted += (checkBox.checked) ? 1 : -1;
			break;
			case "D":
				canBeDeleted += (checkBox.checked) ? 1 : -1;
			break;
		}
	}
	
    ButtonStatus("ntf_notify", canBeSent, "Notify");
    ButtonStatus("ntf_decline", canBeDeclined, "Decline");
    ButtonStatus("ntf_delete", canBeDeleted, "Delete");
}

function Process(action)
{
	document.notifications_list_form.action.value = action;
	document.notifications_list_form.submit();
}

function Notify()
{
	Process("prepare_notifications");
}

function Decline()
{
    message = "Are you sure you want to decline selected requests?";
    message += "\n(" + canBeDeclined + ((canBeDeclined>1) ? " requests are selected)" : " request is selected)");
	if (confirm(message)) {
		Process("decline_notifications");
	}
}

function Delete()
{
    message = "Are you sure you want to delete selected requests?";
    message += "\n(" + canBeDeleted + ((canBeDeleted>1) ? " requests are selected)" : " request is selected)");
    message += "\n\nWARNING! This operation cannot be reverted!";
	if (confirm(message)) {
		Process("delete_notifications");
	}
}

-->
</script>

<form action="admin.php" method="GET" name="notifications_sort_form">
<span FOREACH="getAllParams(#sortby#),name,val">
<input type="hidden" name="{name}" value="{val}">
</span>
<input type=hidden name=sortby value="{sortby}">
<tr class=TableHead>
    <td><input type=checkbox onClick="this.blur();CheckBoxAction(this)"></td>
    <td>&nbsp;&nbsp;&nbsp;Name</td>
    <td><a href="javascript: SortBy('email')" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle" IF="sortby=#email#"/>  <span IF="!sortby=#email#">&nbsp;&nbsp;&nbsp;<u>E-mail</u></span><b IF="sortby=#email#">E-mail</b></a></td>
    <td><a href="javascript: SortBy('date')" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle" IF="sortby=#date#"/> <span IF="!sortby=#date#">&nbsp;&nbsp;&nbsp;<u>Date</u></span><b IF="sortby=#date#">Date</b></a></td>
    <td><a href="javascript: SortBy('type')" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle" IF="sortby=#type#"/> <span IF="!sortby=#type#">&nbsp;&nbsp;&nbsp;<u>Type</u></span><b IF="sortby=#type#">Type</b></a></td>
    <td><a href="javascript: SortBy('status')" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle" IF="sortby=#status#"/> <span IF="!sortby=#status#">&nbsp;&nbsp;&nbsp;<u>Status</u></span><b IF="sortby=#status#">Status</b></a></td>
    <td>&nbsp;&nbsp;&nbsp;Details</td>
</tr>
</form>

<form action="admin.php" method=POST name="notifications_list_form">
<span FOREACH="getAllParams(#action#),name,val">
<input type="hidden" name="{name}" value="{val}">
</span>
<input type="hidden" name="action" value="">

<tbody FOREACH="namedWidgets.pager.pageData,ntf_id,ntf">
<tr>
    <td align=center><input type=checkbox name="selected[{ntf.notify_id}]" value=1 id="notification_{ntf_id}" onClick="this.blur();CheckBoxHandler('{ntf_id}')"></td>
    <td>{ntf.person_info:h}</td>
    <td>
        <table border=0 cellpadding=0 cellspacing=0>
        <tr>
    		<td IF="ntf.profile_id"><img src="images/modules/ProductAdviser/customer_registered.gif" width=15 height=15 border=0 alt="Registered customer"></td>
    		<td IF="!ntf.profile_id"><img src="images/modules/ProductAdviser/customer_unregistered.gif" width=15 height=15 border=0 alt="Unregistered customer"></td>
    		<td nowrap>&nbsp;
    		<a href="admin.php?target=users&mode=search&search=Search&user_type=customer&substring={ntf.email:u}" IF="ntf.profile_id"><u>{ntf.email:h}</u></a>
    		<span IF="!ntf.profile_id">{ntf.email:h}</span>
    		</td>
        </tr>
        </table>
    </td>
    <td nowrap>{time_format(ntf.date)}</td>
    <td align=center>
    <span IF="ntf.type=#product#"><img src="images/modules/ProductAdviser/product.gif" width=14 height=14 border=0></span>
    <span IF="ntf.type=#price#"><img src="images/modules/ProductAdviser/price.gif" width=14 height=18 border=0></span>
    </td>
    <td align=center>
    <span IF="ntf.status=#Q#"><img src="images/modules/ProductAdviser/queued.gif" width=12 height=11 border=0></span>
    <span IF="ntf.status=#U#"><img src="images/modules/ProductAdviser/updated.gif" width=12 height=11 border=0></span>
    <span IF="ntf.status=#S#"><img src="images/modules/ProductAdviser/sent.gif" width=12 height=11 border=0></span>
    <span IF="ntf.status=#D#"><img src="images/modules/ProductAdviser/declined.gif" width=12 height=11 border=0></span>
    </td>
    <td>
    <span IF="ntf.type=#product#">
    <b>Product:&nbsp;</b>{ntf.product.name}
    <span IF="ntf.errorPresent">
    #{ntf.product_id}&nbsp;<font color=red><b>Warning!</b></font> {ntf.errorDescription}
    </span>
    <span IF="!ntf.errorPresent">
    <span IF="ntf.product.productOptionsStr">
    <br><b>Options:&nbsp;</b>{ntf.product.productOptionsStr}
    </span>
    <br><b>Quantity:&nbsp;</b>was <b>{ntf.quantity}</b>, now is <b><span IF="xlite.PA_InventorySupport">{ntf.product.quantity}</span><span IF="!xlite.PA_InventorySupport"><font color=red>n/a</font></span></b>
    </span>
    </span>
    <span IF="ntf.type=#price#">
    <b>Product:&nbsp;</b>{ntf.product.name}
    <span IF="ntf.errorPresent">
    #{ntf.product_id}&nbsp;<font color=red><b>Warning!</b></font> {ntf.errorDescription}
    </span>
    <span IF="!ntf.errorPresent">
    <br><b>Price:&nbsp;</b>was <b>{ntf.price}</b>, now is <b>{ntf.product.price}</b>
    </span>
    </span>
    </td>
    <script language="Javascript">notifications[notifications.length] = "{ntf_id}"; statuses['{ntf_id}'] = "{ntf.status}";</script>
</tr>
<tr class=TableHead>
    <td colspan=7 height=1></td>
</tr>
</tbody>
<tr>
    <td colspan=7>
        <table border=0 cellpadding=3 cellspacing=0>
        <tr>
    		<td width=80%><input type=button id="ntf_notify" value=" Notify " onClick="this.blur();Notify()" disabled></td>
    		<td>&nbsp;&nbsp;</td>
    		<td width=10%><input type=button id="ntf_decline" value=" Decline " onClick="this.blur();Decline()" disabled></td>
    		<td>&nbsp;&nbsp;</td>
    		<td width=10%><input type=button id="ntf_delete" value=" Delete " onClick="this.blur();Delete()" disabled></td>
        </tr>
        </table>
        <br><br>
    </td>
</tr>
</table>

</form>

</span>

<span IF="mode=#process#">

<span align=justify>This section allows you to modify and send notification messages to customers.<br><br></span>

<script language="Javascript">
<!--

var currentEditId = -1;
var currentEdit = "";

function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

function EditMode(preview, ntf_id, status)
{
    var newEdit = "message_" + ((preview)?"preview":"source") + "_" + ntf_id;
	visibleBox(newEdit, status);
}

function StartEdit(ntf_id)
{
	SwitchEditMode(true, ntf_id);

    var newEdit = "message_" + ntf_id;
	EditMode(true, currentEditId, false);
	EditMode(false, currentEditId, false);
	visibleBox(currentEdit, false);

	if (newEdit != currentEdit) {
		currentEditId = ntf_id;
    	currentEdit = newEdit;
		visibleBox(currentEdit, true);
    	newEdit = "message_preview_" + ntf_id;
		EditMode(true, ntf_id, true);
	} else {
    	currentEdit = "";
		currentEditId = -1;
	}
}

function SwitchEditMode(preview, ntf_id)
{
	var status1, status2;

	if (preview) {
		status1 = false;
		status2 = true;
    	var elm1 = document.getElementById("message_preview_container_"+ntf_id);
    	var elm2 = document.getElementById("message_source_container_"+ntf_id);
        if (elm1 && elm2) {
        	elm1.innerHTML = elm2.value;
        }
    	elm1 = document.getElementById("subject_preview_container_"+ntf_id);
    	elm2 = document.getElementById("subject_source_container_"+ntf_id);
        if (elm1 && elm2) {
        	elm1.innerHTML = elm2.value;
        }
	} else {
		status1 = true;
		status2 = false;
	}
	EditMode(false, ntf_id, status1);
	EditMode(true, ntf_id, status2);
}

-->
</script>

<form action="admin.php" method=POST name="notifications_list_form">
<span FOREACH="getAllParams(#action#),name,val">
<input type="hidden" name="{name}" value="{val}">
</span>
<input type="hidden" name="action" value="send_notifications">

<table border=0 cellpadding=1 cellspacing=3>

<tr class=TableHead>
    <td>&nbsp;</td>
    <td>&nbsp;Name</td>
    <td>&nbsp;E-mail</td>
    <td>&nbsp;Date</td>
    <td align=center>Type</td>
    <td align=center>Status</td>
    <td>&nbsp;Details</td>
</tr>
<tbody FOREACH="notifications,ntf_id,ntf">
<tr>
    <td align=center><a href="javascript: StartEdit('{ntf_id}')" onClick="this.blur()"><img src="images/modules/ProductAdviser/edit.gif" width=16 height=16 border=0 alt="Edit mail before send"></td>
    <td>{ntf.person_info:h}</td>
    <td>
        <table border=0 cellpadding=0 cellspacing=0>
        <tr>
    		<td IF="ntf.profile_id"><img src="images/modules/ProductAdviser/customer_registered.gif" width=15 height=15 border=0 alt="Registered customer"></td>
    		<td IF="!ntf.profile_id"><img src="images/modules/ProductAdviser/customer_unregistered.gif" width=15 height=15 border=0 alt="Unregistered customer"></td>
    		<td>&nbsp;{ntf.email:h}</td>
        </tr>
        </table>
    </td>
    <td nowrap>{time_format(ntf.date)}</td>
    <td align=center>
    <span IF="ntf.type=#product#"><img src="images/modules/ProductAdviser/product.gif" width=14 height=14 border=0></span>
    <span IF="ntf.type=#price#"><img src="images/modules/ProductAdviser/price.gif" width=14 height=18 border=0></span>
    </td>
    <td align=center>
    <span IF="ntf.status=#Q#"><img src="images/modules/ProductAdviser/queued.gif" width=12 height=11 border=0></span>
    <span IF="ntf.status=#U#"><img src="images/modules/ProductAdviser/updated.gif" width=12 height=11 border=0></span>
    <span IF="ntf.status=#S#"><img src="images/modules/ProductAdviser/sent.gif" width=12 height=11 border=0></span>
    <span IF="ntf.status=#D#"><img src="images/modules/ProductAdviser/declined.gif" width=12 height=11 border=0></span>
    </td>
    <td>
    <span IF="ntf.type=#product#">
    <b>Product:&nbsp;</b>{ntf.product.name}
    <span IF="ntf.errorPresent">
    #{ntf.product_id}&nbsp;<font color=red><b>Warning!</b></font> {ntf.errorDescription}
    </span>
    <span IF="!ntf.errorPresent">
    <span IF="ntf.product.productOptionsStr">
    <br><b>Options:&nbsp;</b>{ntf.product.productOptionsStr}
    </span>
    <span IF="ntf.quantity">
    <br><b>Quantity:&nbsp;</b>was <b>{ntf.quantity}</b>, now is <b>{ntf.product.quantity}</b>
    </span>
    </span>
    </span>
    <span IF="ntf.type=#price#">
    <b>Product:&nbsp;</b>{ntf.product.name}
    <span IF="ntf.errorPresent">
    #{ntf.product_id}&nbsp;<font color=red><b>Warning!</b></font> {ntf.errorDescription}
    </span>
    <span IF="!ntf.errorPresent">
    <br><b>Price:&nbsp;</b>was <b>{ntf.price}</b>, now is <b>{ntf.product.price}</b>
    </span>
    </span>
	<input type="hidden" name="ids[{ntf.notify_id}]" value="{ntf.notify_id}">
    </td>
</tr>
<tr style="display:none" id="message_{ntf_id}">
    <td colspan=10>
        <table border=0 cellpadding=1 cellspacing=0 width=100%>
        <tbody style="display:none" id="message_source_{ntf_id}">
        <tr>
    		<td nowrap><b>Mail subject:</b>&nbsp;</td>
    		<td><input type=text name="subjects[{ntf.notify_id}]" id="subject_source_container_{ntf_id}" value="{ntf.mail.subject}" size=80></td>
    		<td rowspan=2 align=center valign=middle><input type=button value=" Preview " onClick="this.blur();SwitchEditMode(true,'{ntf_id}');"></td>
        </tr>
        <tr>
    		<td nowrap colspan=2><b>Mail body:</b><br>
<textarea cols=100 rows=20 name="bodies[{ntf.notify_id}]" id="message_source_container_{ntf_id}">{ntf.mail.body}</textarea>    
    		</td>
        </tr>
        </tbody>
        <tbody style="display:none" id="message_preview_{ntf_id}">
        <tr>
    		<td colspan=2 class="SidebarBorder">
        		<table border=0 cellpadding=0 cellspacing=0 class="DialogBox" width=100%>
                	<tr>
                		<td class="TableHead"><div id="subject_preview_container_{ntf_id}">{ntf.mail.subject:h}</div><!-- --></td>
                	</tr>
                </table>
    		</td>
    		<td rowspan=2 align=center valign=middle><input type=button value=" Edit " onClick="this.blur();SwitchEditMode(false,'{ntf_id}');"></td>
        </tr>
        <tr>
    		<td colspan=2 class="SidebarBorder">
        		<table border=0 cellpadding=0 cellspacing=0 class="DialogBox" width=100%>
                	<tr>
                		<td><div id="message_preview_container_{ntf_id}">{ntf.mail.body:h}<!-- --></div></td>
                	</tr>
                </table>
    		</td>
        </tr>
        </tbody>
        </table>
    </td>
</tr>
<tr class=TableHead>
    <td colspan=7 height=1></td>
</tr>
</tbody>
<tr>
    <td colspan=7><input type=submit value=" Send " onClick="this.blur();"</td>
</tr>
</table>

</form>

</span>
