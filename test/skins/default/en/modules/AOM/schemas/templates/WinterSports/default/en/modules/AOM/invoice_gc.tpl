{if:clone}
    <tr IF="!isSelected(cloneOrder,#payedByGC#,#0#)">
        <td nowrap colspan="4" align="right" class="AomProductDetailsTitle">Paid with GC:</td>
        <td align="right">{price_format(cloneOrder,#payedByGC#):h}</td>
    </tr>
{else:}
	<tr IF="!isSelected(order,#payedByGC#,#0#)">
        <td nowrap colspan="4" align="right" class="AomProductDetailsTitle">{if:mode=#invoice#}Paid with GC{else:}{if:!order.gc.validate()=#1#}<a href="{xlite.shopUrl(#cart.php#)}?target=gift_certificate_info&gcid={order.gc.gcid}" onClick="this.blur()"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle"> Paid with GC</a>{else:}<a href="javascript:alert('The \'{order.gcid}\' GC was deleted.')" onClick="this.blur()">Paid with GC <font color="#FF0000">(!)</font></a>{end:}{end:}</td>
		<td align="right">{price_format(order,#payedByGC#):h}</td>
	</tr>
{end:}						

