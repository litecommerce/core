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
<p align=justify>This section displays statistics on commissions paid for a certain period.</p>

<br>

<form name=search_sales_form action="cart.php" method=GET><!-- {{{ -->
<input type="hidden" foreach="allparams,param,v" name="{param}" value="{v}"/>

<table border=0 cellpadding=3>
<tr>
    <td><input type=radio name=period value="" checked="period=##"></td>
    <td>All payments</td>
</tr>
<tr>
    <td><input type=radio name=period value="period" checked="period=#period#"></td>
    <td>Payments for period</td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>
        <table border=0>
        <tr>
            <td>Period from:</td>
            <td><widget class="XLite_View_Date" field="startDate"></td>
        </tr>    
        <tr>
            <td>Period to:</td>
            <td><widget class="XLite_View_Date" field="endDate"></td>
        </tr>    
        </table>
     </td>   
<tr>
    <td colspan=2>&nbsp;<input type=submit name=search value=Search></td>
</tr>
</table>
</form><!-- }}} -->

<br>

<table IF="search&payments" border=0 cellpadding=5 cellspacing=1>
<tr>
    <td colspan=2><widget class="XLite_View_Pager" data="{payments}" name="pager"></td>
</tr>
<tr class=TableHead>
    <td align=center><b>Date</b></td>
    <td align=center><b>Amount</b></td>
</tr>
<tr FOREACH="pager.pageData,payment">
    <td>{date_format(payment.paid_date):h}</td>
    <td align=right>{price_format(payment.amount):h}</td>
</tr>
<tr>
    <td><b>Total paid:</b></td>
    <td align=right><b>{price_format(totalPaid):h}</b></td>
</tr>
</table>

<span IF="search&!payments">0 payment(s) found</span>
