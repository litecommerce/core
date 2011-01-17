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
<!-- ********************************* GIFT CERTIFICATES ********************************* -->

<tbody IF="xlite.auth.profile.activeGiftCertificates">
<tr><td colspan="4">&nbsp;</td></tr>
<tr valign="middle">
    <td colspan="4"><b>Active Gift Certificates</b><br><hr size="1" noshade>
		<div id="btn_view_gift_certs">
		<script type="text/javascript">
function onViewGC() {
	document.getElementById('gift_certs_body').style.display = ''; 
	document.getElementById('btn_view_gift_certs').style.display = 'none';
}
		</script>
		<widget class="\XLite\View\Button" label="View Gift Certificates" href="javascript: onViewGC()">
		</div>
	</td>
</tr>
</tbody>

<tbody id="gift_certs_body" style="display: none;" IF="xlite.auth.profile.activeGiftCertificates">
<tr valign="middle">
    <td colspan="4">
	<table border="0">
	<tr class="TableHead">
		<th nowrap>Gift certificate</th>
		<th nowrap>Rem./Amount</th>
		<th nowrap>Issue date</th>
		<th nowrap>Expiration date</th>
	</tr>

	<tr FOREACH="xlite.auth.profile.activeGiftCertificates,id,cert" class="{getRowClass(id,##,#BottomBox#)}">
		<td>{cert.gcid}<font IF="cert.displayWarning" class="Star">&nbsp;*&nbsp;</font></td>
		<td>{price_format(cert.debit):h}/{price_format(cert.amount):h}</td>
		<td>{formatDate(cert.add_date)}</td>
		<td>{formatDate(cert.expiration_date)}</td>
	</tr>
	</table>
	<font class="Star">&nbsp;*&nbsp;</font><i>- these certificates will expire sooner than in {config.CDev.GiftCertificates.expiration_warning_days} day(s).</i>
    </td>
</tr>
</tbody>
