<tr>
	<td>&nbsp;</td>
	<td colspan=9>
		<table border=0 cellpadding=1 cellspacing=1>
		<tr>
        	<td class="SidebarBorder" colspan=3>
        		<table border=0 cellpadding=1 cellspacing=1 class="SidebarBox">
                	<tr>
						<td><img src="images/modules/ProductAdviser/alert.gif" width=12 height=12 border=0></td>
						<td>&nbsp;</td>
                		<td class="ProductDetails">There <span IF="dialog.isNotifyPresent(product.product_id)=#1#">is</span><span IF="!dialog.isNotifyPresent(product.product_id)=#1#">are</span> <b><font color=blue>{dialog.isNotifyPresent(product.product_id)}</font> Customer Notification<span IF="!dialog.isNotifyPresent(product.product_id)=#1#">s</span></b> awaiting.</span></td>
                		<td>&nbsp;<a href="admin.php?target=CustomerNotifications&type=price&status=U&period=-1&notify_key={dialog.product_id}" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle">&nbsp;<b><u>View request<span IF="!dialog.isNotifyPresent(product.product_id)=#1#">s</span></u></b></a></td>
                	</tr>
                </table>
        	</td>
		</tr>
		</table>
	</td>
</tr>
