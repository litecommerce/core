{* SVN $Id$ *}
<tr IF="!cart.payedByGC=0">
  <td><strong>Paid with GC:</strong></td>
	<td align="right">{price_format(cart,#payedByGC#):h}</td>
</tr>

<tr IF="!cart.payedByGC=0">
  <td colspan="2">
    <widget class="XLite_View_Button" href="{buildURL(#cart#,#remove_gc#,_ARRAY_(#return_target#^target))}" label="Remove GC">
  </td>
</tr>
