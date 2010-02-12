<tr IF="!cart.payedByGC=0"><td><b>Paid with GC:</b></td>
	<td align="right">
		{price_format(cart,#payedByGC#):h}
	</td>
</tr>
<tr IF="!cart.payedByGC=0">
    <td colspan="2">
        <widget class="XLite_View_Button" href="cart.php?target=cart&action=remove_gc&return_target={target}" label="Remove GC">
    </td>
</tr>
