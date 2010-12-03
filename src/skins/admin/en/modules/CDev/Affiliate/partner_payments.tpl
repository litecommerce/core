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
<p align=justify>This section contains information on partner commissions including amounts already paid, amounts approved for payment but not paid, commissions on pending orders and commission limits for each particular partner.</p>

<p align=justify><b>Note:</b> &quot;Ready to be paid&quot; check box will appear when the commission earned by a partner exceeds the minimum limit defined by the partners affiliate plan.</p>

<br>

<form name=export_payments_form action="admin.php" method=POST>
<input type=hidden name=target value=partner_payments>
<input type=hidden name=action value=mark_paid>

<script language=javascript>
function go(account_filter) {
    document.location = "admin.php?target=partner_payments&account_filter=" + account_filter;
}
</script>

<table border=0>
<tr>
<td><input type=radio name=account_filter value="" checked="account_filter=##" onclick="go('')"></td><td>&nbsp;All accounts</td>
<td>&nbsp;&nbsp;&nbsp;<input type=radio name=account_filter value=ready checked="account_filter=#ready#" onclick="go('ready')"></td><td>&nbsp;Accounts &quot;Ready to be paid&quot;</td>
</tr>
</table>

<br><br>

<p>{found} account(s)s found</p>

<table IF="payments" border=0 cellpadding=5 cellspacing=1>
<tr>
    <td colspan=6><widget class="\XLite\View\PagerOrig" data="{payments}" name="pager" itemsPerPage="15"></td>
</tr>
<tr valign=middle class=TableHead>
    <td align=center rowspan=2><b>Partner</b></td>
    <td align=center colspan=4><b>Partner commissions</b></td>
    <td align=center rowspan=2><b>Ready to be paid</b></td>
</tr>
<tr class=TableHead>
    <td align=center>Paid</td>
    <td align=center>Approved</td>
    <td align=center>Pending</td>
    <td align=center>Minimum limit</td>
</tr>
<tr FOREACH="pager.pageData,key,payment" class="{getRowClass(account_filter=##&payment.ready,#TableRow#,##)}" height=25>
    <td><a href="admin.php?target=profile&profile_id={payment.partner.profile_id}"><u>{payment.partner.billing_firstname} {payment.partner.billing_lastname} &lt;{payment.partner.login}&gt;</u></a></td>
    <td align=right>{price_format(payment.paid):h}</td>
    <td align=right>{price_format(payment.approved):h}</td>
    <td align=right>{price_format(payment.pending):h}</td>
    <td align=right>{price_format(payment.min_limit):h}</td>
    <td align=center>
        {if:payment.ready}<input type=checkbox name="payment_paid[]" value="{payment.partner.profile_id}">{else:}-{end:}
    </td>
</tr>
<tr IF="hasReady">
    <td colspan=6><input type=submit value=" Mark as &quot;paid&quot; "></td>
</tr>
</table>

<br><br>
<br><br>

<table border=0>
<tr>
    <td colspan=2 class=AdminTitle>Export partner payments</td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr>
    <td>CSV delimiter:</td><td><widget template="common/delimiter.tpl"></td>
</tr>
<tr>
    <td>&nbsp;</td><td><input type=submit value=Export onclick="document.export_payments_form.action.value='export_payments'"></td>
</tr>
</table>

</form>

<br><br>
<form name=upload_payments_form action="admin.php" method=POST enctype="multipart/form-data">
<input type=hidden name=target value=partner_payments>
<input type=hidden name=action value=payment_upload>

<table border=0>
<tr>
    <td colspan=3 class=AdminTitle>Upload partner payments</td>
</tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr>
    <td colspan=3 align=justify>You can upload information about the payment of commissions to partners. The first column in your CSV file should contain order ID# - an unique order identifier. The second column should contain a partner commission payment flag for this order, the value of which can be either "Y" or "N" ("Y" signifies that the partner commission was paid for this order, "N" - not paid).<br>
    <br>
    Example:<br>
    163;Y<br>
    164;Y<br>
    165;N<br>
    </td>
</tr>    
<tr><td colspan=3>&nbsp;</td></tr>
<tr>
    <td nowrap>CSV delimiter:</td>
    <td><widget template="common/delimiter.tpl"></td>
    <td width="100%">&nbsp;</td>
</tr>
<tr>
    <td nowrap>CSV file:</td>
    <td><input type=file name=userfile></td>
    <td width="100%">&nbsp;</td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=submit value=Upload></td>
    <td width="100%">&nbsp;</td>
</tr>
</table>
</form>
