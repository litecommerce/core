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
<widget class="\XLite\View\Pager" data="{products}" name="pager" itemsPerPage="{xlite.config.General.products_per_page_admin}" />
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
