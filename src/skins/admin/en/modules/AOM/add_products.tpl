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
<html>
<head>
    <title>Search products</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta name="ROBOTS" content="NOINDEX">
    <meta name="ROBOTS" content="NOFOLLOW">
    <LINK href="skins/admin/en/style.css"  rel=stylesheet type=text/css>
</head>
<script>
	function showReloaded()
	{
		if(!isNaN(parseInt("{reloaded}"))) {
            window.opener.location.replace('admin.php?target={target}&order_id={order_id}&page=order_edit&mode=products&pageID=0');
		};
	}
</script>
<body LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 BOTTOMMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 onLoad="showReloaded();">
<widget template="common/dialog.tpl" head="Search product" body="modules/AOM/product/search.tpl">
<p IF="outOfStock">
	<font class="Star">*</font> - "{getOutOfStockProduct(outOfStock)}" product cannot be added as it is out of stock. 
</p>
{if:products}
<span class="Text" IF="mode=#search#">{productsFound} product(s) found</span>
<br>
<widget class="\XLite\View\PagerOrig" data="{products}" name="pager" itemsPerPage="{xlite.config.General.products_per_page_admin}">
<br>
<form name="aom_products_form" action="admin.php" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="add_products">
<input type="hidden" name="mode" value="products">
<table border="0" cellpadding="0" cellspacing="3" width="100%">
<tr class="TableHead">
    <th>&nbsp;</th>
    <th>Product</th>
	<th>Price</th>
</tr>
<tbody FOREACH="pager.pageData,product">
<tr>
    <td>
		<input type="checkbox" name="add_products[]" value="{product.product_id}">
    </td>
    <td>
        <font class="ItemsList">{product.name:h}</font>
    </td>
    <td nowrap align=right>{price_format(product.price):h}</td>
</tr>
</tbody>
<script>
	function checkAddition()
	{
		var parentOrder  = window.opener.identifyOrder();
		var currentOrder = parseInt("{order_id}");
		if (!isNaN(parentOrder) && parentOrder == currentOrder)
		{
			aom_products_form.add_button.disabled = true;
			aom_products_form.close_button.disabled = true;
            window.opener.document.body.innerHTML="<H1 align='center'>Page is reloading, please wait...</H1>";
	        aom_products_form.submit();
		} else {
			alert("This 'Select products' form was opened for adding products to order #" + currentOrder +", but you are editing order #" + parentOrder +".\n This window will be closed so you have to click on 'Add products' button again.")
			window.close();	
		}
		return true;		
	}	
	
</script>
<tr><td colspan=3>&nbsp;</td></tr>
<tr>
	<td colspan=3>
        <input id="add_button" name="add_button" type="button" value=" Add " onClick="checkAddition()">&nbsp;&nbsp;
        <input id="close_button" name="close_button" type="button" value=" Close " onClick="window.close();"></td>
</tr>
</table>
</form>
{end:}
{if:mode=#search#&!products}
No products found on your query.
{end:}
</body>
</html>
