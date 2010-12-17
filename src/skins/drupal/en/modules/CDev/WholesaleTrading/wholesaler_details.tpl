{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<tbody IF="profile.isShowWholesalerFields()">
<tr>
	<td  colspan="2" nowrap bgcolor="#DDDDDD"><b>Wholesaler details</b></td>
</tr>
<tr IF="xlite.config.CDev.WholesaleTrading.WholesalerFieldsTaxId">
	<td nowrap>Sales Permit/Tax ID#</td>
	<td>{profile.tax_id}</td>
</tr>
<tr IF="xlite.config.CDev.WholesaleTrading.WholesalerFieldsVat">
    <td nowrap>VAT Registration number</td>
    <td>{profile.vat_number}</td>
</tr>
<tr IF="xlite.config.CDev.WholesaleTrading.WholesalerFieldsGst">
    <td nowrap>GST Registration number</td>
    <td>{profile.gst_number}</td>
</tr>
<tr IF="xlite.config.CDev.WholesaleTrading.WholesalerFieldsPst">
    <td nowrap>PST Registration number</td>
    <td>{profile.pst_number}</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
</tbody>
