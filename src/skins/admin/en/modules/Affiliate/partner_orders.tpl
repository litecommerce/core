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
<p align=justify>In this section you can review details on orders facilitated by the store partners.</p>

<br>

<form name=partner_orders_form action="admin.php" method=GET>
<input type="hidden" name="target" value="partner_orders">
<input type="hidden" name="mode" value="search">
<input type="hidden" name="action" value="">

<table border=0 cellpadding=3>
<tr>
    <td>Order date from:</td>
    <td><widget class="\XLite\View\Date" field="startDate"></td>
</tr>    
<tr>
    <td>Order date through:</td>
    <td><widget class="\XLite\View\Date" field="endDate"></td>
</tr>    
<tr>
    <td  nowrap height=10>Order id</td>
    <td><input size=6 name=order_id1 value="{order_id1}"> - <input size=6 name=order_id2 value="{order_id2}"> </td>
</tr>
<tr>
    <td>Order status:</td>
    <td><widget class="\XLite\View\StatusSelect" field="status" allOption></td>
</tr>
<tr>
    <td valign=top>Partner:</td>
    <td><widget class="\XLite\Module\Affiliate\View\PartnerSelect" ></td>
</tr>
<tr>
    <td>Payment status:</td>
    <td>
    <table border=0>
    <tr>
        <td><input type=radio name=payment_status value="" checked="payment_status=##"></td>
        <td>All</td>
        <td>&nbsp;<input type=radio name=payment_status value=0 checked="payment_status=#0#"></td>
        <td>Pending</td>
        <td>&nbsp;<input type=radio name=payment_status value=1 checked="payment_status=#1#"></td>
        <td>Paid</td>
    </tr>
    </table>
    </td>
</tr>
<tr>
    <td>CSV delimiter:</td>
    <td><widget template="common/delimiter.tpl"></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>
        <input type=button value=Search onclick="document.partner_orders_form.mode.value='search'; document.partner_orders_form.action.value='';document.partner_orders_form.submit()">
        &nbsp;&nbsp;
        <input type=button value=Export onclick="document.partner_orders_form.action.value='export';document.partner_orders_form.submit()">
    </td>
</tr>
<tr IF="mode=#search#"><td colspan=2>{salesCount} order(s) found</td></tr>
</table>

</form>

<br>

<table IF="mode=#search#&sales" border=0 cellpadding=5 cellspacing=1>
<tr valign=middle class=TableHead>
    <td align=center rowspan=2><b>Partner</b></td>
    <td colspan=2 align=center><b>Order</b></td>
    <td align=center rowspan=2><b>Subtotal</b></td>
    <td align=center rowspan=2><b>Commissions</b></td>
    <td align=center rowspan=2><b>Owner</b></td>
    <td colspan=2 align=center><b>Status</b></td>
</tr>
<tr class=TableHead>
    <td>#</td>
    <td align=center>Date</td>
    <td>Order</td>
    <td>Commissions</td>
</tr>
<tr FOREACH="sales,sidx,sale" class="{getRowClass(sidx,#TableRow#,##)}">
    <td><a href="admin.php?target=profile&profile_id={sale.partner.profile_id}"><u>{sale.partner.login:h}</u></a></td>
    <td><a href="admin.php?target=order&order_id={sale.order.order_id}"><u>{sale.order.order_id}</u></a></td>
    <td><a href="admin.php?target=order&order_id={sale.order.order_id}"><u>{date_format(sale.order.date)}</u></a></td>
    <td align=right>{price_format(sale.order,#subtotal#):h}</td>
    <td align=right>{price_format(sale,#commissions#):h}</td>
    <td>
        {if:sale.affiliate=#0#}
        Affiliate
        {else:}
        Child (<a href="admin.php?target=profile&profile_id={sale.affiliates.1.profile_id}"><u>{sale.affiliates.1.login:h}</u></a>)
        {end:}
    </td>
    <td><widget template="common/order_status.tpl" order="{sale.order}"></td>
    <td>{if:sale.paid=#0#}Pending{else:}Paid{end:}</td>
</tr>
</table>
