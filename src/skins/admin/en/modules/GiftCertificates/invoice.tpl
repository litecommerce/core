    <tr IF="!isSelected(order,#payedByGC#,#0#)">
        <td nowrap>{if:mode=#invoice#}Paid with GC{else:}{if:!order.gc.validate()=#1#}<a href="admin.php?target=gift_certificate&gcid={order.gc.gcid}" onClick="this.blur()"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle"> Paid with GC</a>{else:}<a href="javascript:alert('The \'{order.gcid}\' GC was deleted.')" onClick="this.blur()">Paid with GC <font color="#FF0000">(!)</font></a>{end:}{end:}</td>
        <td>{price_format(order,#payedByGC#):h}</td>
    </tr>

