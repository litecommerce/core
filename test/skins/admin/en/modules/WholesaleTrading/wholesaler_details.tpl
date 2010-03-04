<tbody IF="profile.isShowWholesalerFields()">
<tr>
	<td  colspan="2" nowrap bgcolor="#DDDDDD"><b>Wholesaler details</b></td>
</tr>
<tr IF="xlite.config.WholesaleTrading.WholesalerFieldsTaxId">
	<td nowrap>Sales Permit/Tax ID#</td>
	<td>{profile.tax_id}</td>
</tr>
<tr IF="xlite.config.WholesaleTrading.WholesalerFieldsVat">
    <td nowrap>VAT Registration number</td>
    <td>{profile.vat_number}</td>
</tr>
<tr IF="xlite.config.WholesaleTrading.WholesalerFieldsGst">
    <td nowrap>GST Registration number</td>
    <td>{profile.gst_number}</td>
</tr>
<tr IF="xlite.config.WholesaleTrading.WholesalerFieldsPst">
    <td nowrap>PST Registration number</td>
    <td>{profile.pst_number}</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
</tbody>
