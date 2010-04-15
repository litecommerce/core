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
<p align=justify>This section displays statistics on product sales for the specified period.</p>

<br>

<a name=report_form></a>

<form name=ecommerce_report_form action="admin.php" method=POST>
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}"/>
<input type=hidden name=action value=get_data>

<table border="0" cellpadding="1" cellspacing="3">

<widget template="modules/EcommerceReports/period_form.tpl">

<widget template="modules/EcommerceReports/categories_form.tpl">

<widget module="ProductOptions" template="modules/EcommerceReports/options_form.tpl">

<tr>
	<td>Sort by:</td>
	<td>
		<select name="sort_by">
			<option value="amount" selected="{sort_by=#amount#}">Quantity</option>
			<option value="total" selected="{sort_by=#total#}">Total</option>
		</select>
	</td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=submit name=search value=Search></td>
</tr>
</table>
</form>

<br>

<a name="result"></a>

<p IF="search">{count(productSales)} product(s) found</p>

<p IF="search&productSales">
<table border=0 cellpadding=5 cellspacing=1>
<tr class="TableHead">
	<td>Product name</td>
	<td align=center>Qty</td>
	<td align=center>Average price</td>
	<td align=center>Total</td>
</tr>	
<tr FOREACH="productSales,pidx,ps" valign=top class="{getRowClass(pidx,#TableRow#,##)}">
	<td>
        <a href="admin.php?target=product&product_id={ps.product_id}"><u>{ps.order_item.product.name:h}</u></a>
        <widget module="ProductOptions" template="modules/EcommerceReports/selected_options.tpl" item="{ps.order_item}" visible="{split_options}">
    </td>
	<td align="center">{ps.amount}</td>
	<td align="right">{price_format(ps.avg_price):h}</td>
	<td align="right">{price_format(ps.total):h}</td>
</tr>
<tr valign=top class="TableRow">
    <td align=right><b>Total:</b></td>
    <td align="center"><b>{sumTotal(#amount#)}</b></td>
    <td align="right"><b>{price_format(getAveragePrice(#total#,#amount#)):h}</b></td>
    <td align="right"><b>{price_format(sumTotal(#total#)):h}</b></td>
</tr>
</table>
</p>
