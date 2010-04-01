<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
            <td align="left" class="OrderTitle" style="font-size: 20px">Order #{order.order_id:h}: Gift Certificates</td>
	        <td IF="target=#order#" align="right" valign="center"><widget template="modules/AOM/common/clone_button.tpl"></td>
    </tr>   
</table>    
<br>
<form name="delete_gc_form" action="admin.php" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="delete_gc">
<input type="hidden" name="mode" value="gc">
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
    <td colspan="3" class="OrderTitle" style="font-size: 14px">Purchased Gift Certificates</td>
</tr>
<tr valign="top">
    <td colspan="3"><hr style="background-color: #516176; height: 2px"></td>
</tr>
<tr height="25">
    <td IF="target=#order#" width="49%" class="AomTableHead"><b style="font-size: 12px">Original</b></td>
	<td IF="target=#order#">&nbsp;</td>
    <td width="49%" class="AomTableHead"><b style="font-size: 12px">{if:target=#order#}Current{else:}Properties{end:}</b></td>
</tr>
<tr>
	<td valign="top" IF="target=#order#&order.orderGC">
		<span width="100%" FOREACH="order.orderGC,key,gc">
			<widget template="modules/AOM/gift_info.tpl" gc="{gc}">
		</span>
	</td>
    <td valign="top" class="ProductDetailsTitle" IF="target=#order#&!order.orderGC">No Gift Certificates purchased</td>
	<td IF="target=#order#">&nbsp;</td>
	<td valign="top" IF="cloneOrder.orderGC">
        <span width="100%" FOREACH="cloneOrder.orderGC,key,gc">
            <widget template="modules/AOM/gift_info.tpl" gc="{gc}" clone="1">
        </span>
		<table>
			<tr>
				<td>
					<input type="submit" value=" Delete ">
				</td>
			</tr>	
		</table>	
	</td>
    <td valign="top" class="ProductDetailsTitle" IF="!cloneOrder.orderGC">No Gift Certificates purchased</td>
</tr>
</table>
</form>
<form name="clean_gc_form" action="admin.php" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="clean_gc">
<input type="hidden" name="mode" value="gc">
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
    <td colspan="3" class="OrderTitle" style="font-size: 14px">Applied Gift Certificates</td>
</tr>
<tr valign="top">
    <td colspan="3"><hr style="background-color: #516176; height: 2px"></td>
</tr>
<tr height="25">
    <td IF="target=#order#" width="49%" class="AomTableHead"><b style="font-size: 12px">Original</b></td>
    <td IF="target=#order#">&nbsp;</td>
    <td width="49%" class="AomTableHead"><b style="font-size: 12px">{if:target=#order#}Current{else:}Properties{end:}</b></td>
</tr>
<tr>
	<td valign="top" IF="target=#order#&order.GC">
    {if:!order.gc.validate()=#1#}
		<widget template="modules/AOM/gift_info.tpl" gc="{order.GC}">
    {else:}
        GiftCertificate #{order.gcid} was deleted.
    {end:}
	</td>
    <td valign="top" class="ProductDetailsTitle" IF="target=#order#&!order.GC">No Gift Certificates applied</td>
	<td IF="target=#order#">&nbsp;</td>
	<td valign="top" IF="cloneOrder.getGCCopy()">
		{if:cloneOrder.gcCopy.validate()=#1#}
		<widget template="modules/AOM/gift_info.tpl" gc="{cloneOrder.getGCCopy()}">
		<table>
			<tr>
				<td>
					<input type="submit" value=" Delete ">
				</td>
			</tr>	
		</table>	
        {else:}
            GiftCertificate #{cloneOrder.gcid} was deleted.
        {end:}
	</td>
    <td valign="top" class="ProductDetailsTitle" IF="!cloneOrder.getGCCopy()">No Gift Certificates applied</td>
</tr>
</table>
</form>
<br>
<br>
<font IF="cloneOrder.shipping_id=#-1#" class="Star">WARNING! To use a gift certificate for order payment please choose a valid delivery method on the "Order totals" page and click "Calculate/Update" button!</font>
<form action="admin.php" name="gc_form" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="mode" value="search_gc">
<table cellpadding="0" cellspacing="3">
<tr>
	<th>Gift Certificate ID:</th>
	<td width="150"><input class="Input" type="text" name="gcid" value=""></td>
	<td><input type="submit" class="UpdateButton" value=" Search "></td>
</tr>
</table>
</form>
<span IF="mode=#search_gc#&!giftCertificates">
    No Gift Certificates found on your query.
</span>
<span IF="giftCertificates">
	<widget class="XLite_View_Pager" data="{giftCertificates}" name="pager">
	<form action="admin.php" method="POST" name="add_form">
	<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
    <input type="hidden" name="mode" value="gc">
    <input type="hidden" name="action" value="add_gc">
<table cellpadding="0" cellspacing="3">
    <tr class="TableHead">
        <th>&nbsp;</th>
        <th>Gift Certificate ID</th>
		<th>Amount</th>
		<th>Remainder</th>
    </tr>
    <tr foreach="pager.pageData,gc">
	    <td align="center"><input type="radio" name=add_gcid value="{gc.gcid}"></td>
        <td align="right">{gc.gcid:h}</td>
        <td align="right">{price_format(gc.amount):h}</td>
        <td align="right">{price_format(gc.debit):h}</td>
    </tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
</table>
<widget name="pager">
	<input type="submit" value=" Add to Order">&nbsp;&nbsp;<input type="button" value="Use for order payment" onClick="javascript: document.add_form.action.value = 'pay_gc'; document.add_form.submit();" {if:cloneOrder.shipping_id=#-1#}disabled{end:}>
	</form>
</span>
<br>
<table width="100%" cellpadding="0" cellspacing="0">
    <tr height="2" class="TableRow">
	    <td colspan="2"><img src="images/spacer.gif" width="1" height="1" border="0"></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td><a class="AomMenu" href="javascript: Previous();"><img src="images/back.gif" width="13" height="13" border="0" align="absmiddle">&nbsp;</a><a class="AomMenu" href="javascript: Previous();" id="gc_prev">Previous</a></td>   
        <td align="right"><a class="AomMenu" href="javascript: Next();" id="gc_next">Next</a><a class="AomMenu" href="javascript: Next();">&nbsp;<img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"></a></td>
    </tr> 
</table>
<table width="100%" IF="isCloneUpdated()&!cloneOrder.isEmpty()">
    <tr>
        <td align="right" valign="center">
            <font class="Star">(*)</font> <a class="AomMenu" href="admin.php?target={target}&order_id={order_id}&page=order_preview">Review and Save Order&nbsp;<img src="images/go.gif" width="13" height="13" border="0" align="middle"></a>
        </td>
    </tr>
</table>
