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
<form action="admin.php" name="del_dc_form" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="del_dc">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
	    <td align="left" class="OrderTitle" style="font-size: 20px">Order #{order.order_id:h}: Discount coupon</td>
		<td IF="target=#order#" align="right" valign="center"><widget template="modules/AOM/common/clone_button.tpl"></td>
    </tr>   
</table>    
<br>
<table width="100%" cellpadding="0" cellspacing="2">
<tr height="25">
    <td IF="target=#order#" width="49%" class="TableHead"><b style="font-size: 12px">Original</b></td>
	<td IF="target=#order#">&nbsp;</td>
    <td width="49%" class="TableHead"><b style="font-size: 12px">{if:target=#order#}Current{else:}Properties{end:}</b></td>
</tr>
<tr height="25">
	<td IF="target=#order#" class="ProductDetailsTitle" valign="top">{if:order.orderDC}<widget DC="{order.orderDC}" template="modules/AOM/coupon_info.tpl">{else:}No discount coupon applied{end:}</td>
	<td IF="target=#order#">&nbsp;</td>
	<td class="ProductDetailsTitle" valign="top">
	{if:cloneOrder.orderDC}<widget DC="{cloneOrder.orderDC}" template="modules/AOM/coupon_info.tpl" clone="1">{else:}No discount coupon applied{end:}
	</td>
	</tr>
</table>
<br>
</form>

{if:!valid}
<table border="0" cellpadding="3" cellspacing="3">
<tr>
	<td valign="top"><font class="ErrorMessage">Error:</font></td>
	<td valign="top">The discount coupon <b>{wrongDC.coupon}</b> cannot be applied because the condition it requires is not met.<br>It has been removed from your order.</td>
</tr>
<tr IF="wrongDCtotal">
	<td valign="top"><b>Note:</b></td>
	<td >Coupon applies to order total, but now order total amount is unavailable.<br><a href="javascript: changeMode('totals')" onClick="this.blur()"><u>Please choose</u></a> a valid payment or delivery method.</td>
</tr>
</table>
<br>
{end:}

<form action="admin.php" name="dc_form" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="mode" value="search_dc">
<table cellpadding="0" cellspacing="3">
<tr>
    <td><b>Discount coupon:</b></td>
    <td width="150"><input class="Input" type="text" name="coupon" value=""></td>
    <td><input type="submit" class="UpdateButton" value=" Search "></td>
</tr>
</table>
</form>
<span IF="mode=#search_dc#&!discountCoupons">
    No Discount Coupons found on your query.
</span>
<span IF="discountCoupons">
    <widget class="XLite_View_Pager" data="{discountCoupons}" name="pager">
    <form action="admin.php" method="POST" name="add_dc_form">
    <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
    <input type="hidden" name="mode" value="dc">
    <input type="hidden" name="action" value="add_dc">
<table cellpadding="0" cellspacing="3">
    <tr class="TableHead">
        <th>&nbsp;</th>
        <th>Discount Coupons</th>
    </tr>
    <tr foreach="pager.pageData,dc">
        <td align="center"><input type="radio" name=add_dc value="{dc.coupon_id}"></td>
        <td>{dc.coupon:h}</td>
    </tr>
</table>
<widget name="pager">
    <input type="submit" value=" Apply coupon ">
    </form>
</span>
<br>
<table width="100%" cellpadding="0" cellspacing="0">
    <tr height="2" class="TableRow">
	<td colspan="2"><img src="images/spacer.gif" width="1" height="1" border="0"></td>
    </tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
    <tr>
        <td><a class="AomMenu" href="javascript: Previous();"><img src="images/back.gif" width="13" height="13" border="0" align="absmiddle">&nbsp;</a><a class="AomMenu" href="javascript: Previous();" id="dc_prev">Previous</a></td>  
        <td align="right"><a class="AomMenu" href="javascript: Next();" id="dc_next">Next</a><a class="AomMenu" href="javascript: Next();">&nbsp;<img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"></a></td>
    </tr>
</table>
<table width="100%" IF="isCloneUpdated()&!cloneOrder.isEmpty()">
    <tr>
        <td align="right" valign="center">
            <font class="Star">(*)</font> <a class="AomMenu" href="admin.php?target={target}&order_id={order_id}&page=order_preview">Review and Save Order&nbsp;<img src="images/go.gif" width="13" height="13" border="0" align="middle"></a>
        </td>   
    </tr>
</table>
