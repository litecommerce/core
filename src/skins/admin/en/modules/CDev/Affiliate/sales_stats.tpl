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
<p align=justify>This section displays statistics on the products sold to the customers who visited the store through a partner's link. Here you can see the list of all the products and the list of top products. This allows you to define which products sell best through the partnership system.</p>

<br>

<form name=search_sales_form action="admin.php" method=GET><!-- {{{ -->
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}"/>

<table border=0 cellpadding=3>
<tr>
    <td>Period from:</td>
    <td><widget class="\XLite\View\Date" field="startDate"></td>
</tr>    
<tr>
    <td>Period to:</td>
    <td><widget class="\XLite\View\Date" field="endDate"></td>
</tr>    
<tr>
    <td valign=top>Partner:</td>
    <td><widget class="\XLite\Module\CDev\Affiliate\View\PartnerSelect" ></td>
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
    <td>Show parent:</td>
    <td><input type=checkbox name=show_parent value=1 checked="show_parent=#1#"></td>
</tr>
<tr>
    <td>Product:</td>
    <td><widget class="\XLite\View\ProductSelect" formName="search_sales_form" formField="product"></td>
</tr>
<tr>
    <td>Show &quot;Top 10&quot; products:</td>
    <td>
    <table border=0>
    <tr>
        <td><input type=checkbox name=show_top_products value=1 checked="show_top_products=#1#"></td>
        <td> &nbsp;sort by</td>
        <td>
            <select name=sort_by>
                <option value=total selected="sort_by=#total#">Total</option>
                <option value=amount selected="sort_by=#amount#">Amount</option>
                <option value=commissions selected="sort_by=#commissions#">Partner commissions</option>
            </select>
        </td>
    </tr>    
    </table>    
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=submit name=search value=Search></td>
</tr>
</table>
</form><!-- }}} -->

<br>

<table IF="search&salesStats&!show_top_products" border=0 cellpadding=5 cellspacing=1>
<tr class=TableHead valign=middle>
    <td align=center rowspan=2><b>Partner</b></td>
    <td IF="show_parent" align=center rowspan=2><b>Parent</b></td>
    <td align=center colspan=4><b>Order</b></td>
    <td align=center rowspan=2><b>Total</b></td>
    <td align=center rowspan=2><b>Commissions</b></td>
    <td align=center rowspan=2><b>Status</b></td>
</tr>
<tr class=TableHead>
    <td>#</td>
    <td align=center>Date</td>
    <td align=center>Product(s)</td>
    <td align=center>QTY.</td>
</tr>
<tr FOREACH="salesStats,key,stat" class="{getRowClass(key,#TableRow#,##)}" valign=top>
    <td><a href="admin.php?target=profile&profile_id={stat.partner.profile_id}"><u>{stat.partner.login:h}</u></a></td>
    <td IF="show_parent"><a href="admin.php?target=profile&profile_id={stat.partner.parentProfile.profile_id}"><u>{stat.partner.parentProfile.login:h}</u></a></td>
    <td><a href="admin.php?target=order&order_id={stat.order.order_id}"><u>{stat.order.order_id}</u></a></td>
    <td align=center><a href="admin.php?target=order&order_id={stat.order.order_id}"><u>{formatDate(stat.order.date)}</u></a></td>
    <td nowrap>
        {foreach:stat.order.items,oidx,oitem}
            {if:oitem.product_id}
            <a href="admin.php?target=product&product_id={oitem.product_id}"><u>{truncate(oitem.product,#name#,#35#):h}</u></a><br>
            {end:}
        {end:}
    </td>
    <td align=center>
        {foreach:stat.order.items,oitem}
            {if:oitem.product_id}
            {oitem.amount}<br>
            {end:}
        {end:}
    </td>
    <td align=right nowrap>{price_format(stat.order,#subtotal#):h}</td>
    <td align=right nowrap>{price_format(stat,#commissions#):h}</td>
    <td align=center>
        <font IF="stat.paid=0" color=blue>Pending</font>
        <font IF="stat.paid=1" color=green>Paid</font>
    </td>
</tr>
<tr class=AdminHead>
    <td>&nbsp;</td>
    <td IF="show_parent">&nbsp;</td>
    <td align=right nowrap colspan=3><b>Total:</b></td>
    <td align=center>{qty}</td>
    <td align=right nowrap>{price_format(salesTotal)}</td>
    <td align=right nowrap>{price_format(commissionsTotal)}</td>
    <td>&nbsp;</td>
</tr>
</table>

<table IF="search&salesStats&show_top_products" border=0 cellpadding=5 cellspacing=1>
<tr class=TableHead>
    <td><b>Product</b></td>
    <td align=center><b>Amount</b></td>
    <td align=center><b>Total</b></td>
    <td align=center><b>Commissions</b></td>
</tr>
<tr FOREACH="topProducts,stat">
    <td>{stat.name:h}</td>
    <td align=center>{stat.amount}</td>
    <td align=right>{price_format(stat.total):h}</td>
    <td align=right>{price_format(stat.commissions):h}</td>
</tr>
</table>
