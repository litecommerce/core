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
<p align=justify>This section displays general shop statistics for the specified period.</p>

<br>

<a name=report_form></a>

<form name=ecommerce_report_form action="admin.php" method=GET>
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}"/>

<table border="0" cellpadding="1" cellspacing="3">
<widget template="modules/EcommerceReports/period_form.tpl">
<tr>
    <td>&nbsp;</td>
    <td><input type=submit name=search value="Show stats"></td>
</tr>
</table>

<br>

<a name="result"></a>

<p IF="search">
<table border=0 cellpadding=5 cellspacing=1>
<tr>
    <td><b>Total orders placed:</b></td>
    <td align=right><b>{gs.placed}</b></td>
</tr>
<tr>
    <td>&nbsp;&nbsp;Orders queued:</td>
    <td align=right>{gs.queued}</td>
</tr>
<tr>
    <td>&nbsp;&nbsp;Orders processed:</td>
    <td align=right>{gs.processed}</td>
</tr>
<tr>
    <td>&nbsp;&nbsp;Orders incomplete:</td>
    <td align=right>{gs.incomplete}</td>
</tr>
<tr>
    <td>&nbsp;&nbsp;Orders failed:</td>
    <td align=right>{gs.failed}</td>
</tr>
<tr>
    <td>&nbsp;&nbsp;Orders declined:</td>
    <td align=right>{gs.declined}</td>
</tr>
<tr>
    <td>&nbsp;&nbsp;Orders completed:</td>
    <td align=right>{gs.completed}</td>
</tr>
<tr>
    <td><b>Gross subtotal:</b></td>
    <td align=right><b>{price_format(gs.subtotal):h}</b></td>
</tr>

<tr>
    <td><b>Total tax:</b></td>
    <td align=right><b>{price_format(gs.total_tax):h}</b></td>
</tr>

<!-- taxes details -->
<tr FOREACH="gs.taxDetails,_txn,_txv">
    <td>&nbsp;&nbsp;Tax {_txn:h}:</td>
    <td align=right>{price_format(_txv):h}</td>
</tr>

<tr>
    <td><b>Shipping charges:</b></td>
    <td align=right><b>{price_format(gs.total_shipping):h}</b></td>
</tr>
<tr>
    <td><b>Total discounts:</b></td>
    <td align=right><b>{price_format(gs.total_discounts):h}</b></td>
</tr>
<tr>
    <td>&nbsp;&nbsp;Discount:</td>
    <td align=right>{price_format(gs.discount):h}</td>
</tr>
<tr>
    <td>&nbsp;&nbsp;Paid with bonus points:</td>
    <td align=right>{price_format(gs.payedByPoints):h}</td>
</tr>
<tr>
    <td>&nbsp;&nbsp;Paid with gift cert.:</td>
    <td align=right>{price_format(gs.payedByGC):h}</td>
</tr>
<tr>
    <td>&nbsp;&nbsp;Wholesaler global discount:</td>
    <td align=right>{price_format(gs.global_discount):h}</td>
</tr>
<!-- extra GS -->
<tr FOREACH="extraGS,esn,esv">
    <td>{esn:h}</td>
    <td>{esv:h}<td>
</tr>
<tr>
    <td><b>Gross Total sales:</b></td>
    <td align=right><b>{price_format(gs.gross_total):h}</b></td>
</tr>
<tr>
    <td><b>Active members accounts:</b></td>
    <td align=right><b>{gs.active_accounts}</b></td>
</tr>
<tr>
    <td><b>New member's accounts registered:</b></td>
    <td align=right><b>{gs.new_accounts}</b></td>
</tr>
</table>
</p>
