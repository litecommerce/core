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
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
            <td align="left" class="OrderTitle" style="font-size: 20px">Order #{order.order_id:h}: Products</td>
	        <td IF="target=#order#" align="right" valign="center"><widget template="modules/CDev/AOM/common/clone_button.tpl"></td>
    </tr>   
</table>    
<br>
<script>
	var	product_window;
	var split_items = new Array();
	
	function identifyOrder()
	{
		return parseInt("{order_id}");
	}

	function showProductsForm()
	{
		if (product_window) product_window.close();
		product_window = window.open("admin.php?target={target}&mode=show&order_id={order_id}","select_products","width=500,height=500,scrollbars=yes");
	}

	function populateArray(key)
	{
		var index = -1;
		var i;
		for (i = 0; i < split_items.length; i++) 
			if (split_items[i] == key) index = i;
		index >= 0 ? split_items.splice(index,1) : split_items.push(key);
	}

	function checkSplit()
	{
		if (split_items.length == 0) {
			alert("Products are not selected");
			return false;
		}

		if (split_items.length < parseInt("{order.productItemsCount}")) {
			document.products_form.action.value = 'split_order'; 
			document.products_form.submit();	
		} else {
			alert("You can not move all products to the new order.");
			return false;	
		}	
		return true;
	}
</script>
<form action="admin.php" method="POST" name="products_form">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="update_products">
<input type="hidden" name="mode" value="products">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td IF="target=#order#" width="49%" height="25" class="AomTableHead">&nbsp;Original products</b></td>		
		<td IF="target=#order#" width="2%">&nbsp;</td>
		<td width="49%" height="25" class="AomTableHead">&nbsp;{if:target=#order#}Current products{else:}Properties{end:}</td>
	</tr>	
	<tr>	
        <td IF="target=#order#">&nbsp;</td>
        <td IF="target=#order#">&nbsp;</td>
        <td>&nbsp;</td>
	</tr>	
<tbody FOREACH="ordersItems,key,set">
	<tr>
		<td IF="target=#order#" valign="top">
		<widget template="modules/CDev/AOM/item.tpl" item="{set.orderItem}" clone="0">
		</td>
        <td IF="target=#order#">&nbsp;</td>
		<td valign="top">
		<widget template="modules/CDev/AOM/item.tpl" item="{set.cloneItem}" clone="1">
		</td>
	</tr>
	<tr>
        <td IF="target=#order#"><hr IF="!isLast(key)" color="#516176"/></td>
		<td IF="target=#order#">&nbsp;</td>
		<td><hr IF="!isLast(key)" color="#516176"/></td>
	</tr>
</tbody>	
<tbody IF="!ordersItems">
    <tr>
        <td IF="target=#order#" class="OrderTitle" valign="top">No products</td>
        <td IF="target=#order#">&nbsp;</td>
        <td class="OrderTitle" valign="top">No products</td> 
	</tr>
    <tr>
		<td IF="target=#order#">&nbsp;</td>
        <td IF="target=#order#">&nbsp;</td>
		<td>&nbsp;</td>
    </tr>
</tbody>		
	<tr>
		<td height="25" class="AomTableHead" IF="target=#order#">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="AomTableHead">
        <tr valign="middle">
            <td>{if:order.split&!isCloneUpdated()}&nbsp;<input type="button" value="Split selected into new order" onClick="javascript: checkSplit();">{else:}<input type="button" value="Split selected into new order" style="font-weight: normal; color: #9b9b9b;" disabled>{end:}
			</td>
        </tr>
        </table>
		</td>
		<td IF="target=#order#">&nbsp;</td>
		<td height="25" class="AomTableHead">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="AomTableHead">
		<tr valign="middle">
			<td>&nbsp;<input type="button" value=" Add products " onClick="javascript: showProductsForm();">&nbsp;&nbsp;<span IF="cloneOrder.productItemsCount"><input type="button" value=" Delete selected " onClick="javascript: document.products_form.action.value = 'delete_products'; document.products_form.submit();">&nbsp;&nbsp;<input type="button" class="UpdateButton" value=" Update " onClick="javascript: document.products_form.submit();"></span></td>
		</tr>
		</table>	
		</td>
	</tr>
</table>
</form>
<br>
<table width="100%" cellspacing="0" cellpadding="0">
    <tr height="2" class="TableRow">
    <td><img src="images/spacer.gif" width="1" height="1" border="0"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td align="right"><a class="AomMenu" href="javascript: Next();" id="products_next">Next</a><a class="AomMenu" href="javascript: Next();">&nbsp;<img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"></a></td>
    </tr>
</table>
<table width="100%" IF="isCloneUpdated()&!cloneOrder.isEmpty()">
    <tr>
        <td align="right" valign="center">
            <font class="Star">(*)</font> <a class="AomMenu" href="admin.php?target={target}&order_id={order_id}&page=order_preview">Review and Save Order&nbsp;<img src="images/go.gif" width="13" height="13" border="0" align="middle"></a>
        </td>
    </tr>
</table>
