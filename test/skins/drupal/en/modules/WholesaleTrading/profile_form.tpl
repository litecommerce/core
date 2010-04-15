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
<tbody IF="isShowWholesalerFields()">
<tr>
    <td colspan="4">&nbsp;</td>
</tr>
<tr valign="middle">
    <td colspan="4"><b>Please fill corresponding fields when applying as wholesaler</b><br><hr size="1" noshade></td>
</tr>
<tr  IF="xlite.config.WholesaleTrading.WholesalerFieldsTaxId" valign="middle">
    <td align="right">Sales Permit/Tax ID#</td>
    <td>&nbsp;</td>
    <td>
	<input name="tax_id" size="32" value="{tax_id}">
	</td>
</tr>
<tr IF="xlite.config.WholesaleTrading.WholesalerFieldsVat" valign="middle">
    <td align="right">VAT Registration number</td>
    <td>&nbsp;</td>
    <td>
	<input name="vat_number" size="32" value="{vat_number}">
	</td>
</tr>
<tr IF="xlite.config.WholesaleTrading.WholesalerFieldsGst" valign="middle">
    <td align="right">GST Registration number</td>
    <td>&nbsp;</td>
    <td>
	<input name="gst_number" size="32" value="{gst_number}">
	</td>
</tr>
<tr IF="xlite.config.WholesaleTrading.WholesalerFieldsPst" valign="middle">
    <td align="right">PST Registration number</td>
    <td>&nbsp;</td>
    <td>
	<input name="pst_number" size="32" value="{pst_number}">
	</td>
</tr>
</tbody>
