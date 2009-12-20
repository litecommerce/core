<tr>
	<td>&nbsp;</td>
	<td>
		<table border=0 cellpadding=1 cellspacing=1>
        	<tr>
				<td>&nbsp;</td>
            	<td class="SidebarBorder">
            		<table border=0 cellpadding=1 cellspacing=1 class="SidebarBox" width=100%>
                    	<tr>
            				<td rowspan=2><img src="images/modules/ProductAdviser/alert.gif" width=12 height=12 border=0></td>
                    		<td class="ProductDetails">
                    		There <span IF="widget.dialog.isNotifyPresent(widget.inventory.inventory_id)=#1#">is</span><span IF="!widget.dialog.isNotifyPresent(widget.inventory.inventory_id)=#1#">are</span> <b><font color=blue>{widget.dialog.isNotifyPresent(widget.inventory.inventory_id)}</font> Customer Notification<span IF="!widget.dialog.isNotifyPresent(widget.inventory.inventory_id)=#1#">s</span></b> awaiting.</span>
                    		</td>
                    	</tr>
                    	<tr>
            				<td align=right><a href="admin.php?target=CustomerNotifications&type=product&status=U&period=-1&notify_key={widget.inventory.inventory_id:u}" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"><b><u>View request<span IF="!widget.dialog.isNotifyPresent(widget.inventory.inventory_id)=#1#">s</span></u></b></a></td>
                    	</tr>
                    </table>
            	</td>
        	</tr>
        </table>
	</td>
</tr>
