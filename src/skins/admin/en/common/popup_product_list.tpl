<script>
var formField = '{formField}_id';
var formName = '{formName}';
var spanName = '{spanName}';
// <!--
function selectProduct(product_id, name)
{
	eval('window.opener.document.' + formName + "." + formField + ".value=" + product_id);
	eval('window.opener.document.getElementById("' + spanName  + '").innerHTML="' + name.replace(/"/g,"\\\"") + '"');
	window.opener.focus();
	window.close();
}
// -->
</script>
<widget class="CPager" data="{products}" name="pager" itemsPerPage="{xlite.config.General.products_per_page_admin}" />
<table border="0">
<tr class="TableHead">
    <td>Sku</td>
    <td>Product</td>
    <td nowrap>Price</td>
</tr>
<tr FOREACH="pager.pageData,product">
    <td width=1%><a href="javascript: selectProduct({product.product_id},'{addSlashes(product,#name#):r}')">{product.sku}</a></td>
    <td width="70%">
        <a href="javascript: selectProduct({product.product_id},'{addSlashes(product.name):r}')"><font class="ItemsList">{product.name}</font></a>
    </td>
    <td nowrap align="right">
        <a href="javascript: selectProduct({product.product_id},'{addSlashes(product.name):r}')">{price_format(product.price):h}</a>
    </td>
</tr>
</table>
