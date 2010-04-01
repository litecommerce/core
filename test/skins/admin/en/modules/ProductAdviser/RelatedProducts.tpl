<span IF="!product.RelatedProducts">
No related products found.
<hr>
</span>

<span IF="product.RelatedProducts">

<script language="Javascript">
<!--

var related_products = new Array(); 
var selected_related_products = 0; 

function ProcessAll(status)
{
    for (var i=0; i<related_products.length; i++) {
    	var Element = document.getElementById(related_products[i]);
    	if (Element) {
        	Element.checked = status;
        }
    	selected_related_products += (status) ? 1 : -1;
    }
    if (selected_related_products < 0) {
    	selected_related_products = 0;
    }
    if (selected_related_products > related_products.length) {
    	selected_related_products = related_products.length;
    }
}

function CheckAll()
{
	ProcessAll(true);
}

function UncheckAll()
{
	ProcessAll(false);
}

function RPCheckBoxChanged(elm)
{
	var delta = (elm.checked) ? 1 : (-1);
    selected_related_products += delta;
}

function ProcessDelete()
{
	if (selected_related_products == 0) {
		alert("You should select the related products you want to delete.");
		return;
	}

	if (confirm('Are you sure you want to delete selected products?')) {
		document.related_product.action.value = "delete_related_products";
		document.related_product.submit();
	}
}

-->
</script>

<table border=0 cellpadding=0 cellspacing=0 width="80%">
<form action="admin.php" method="POST" name="related_product">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="update_related_products">
<tr>
	<td bgcolor=#dddddd>
		<table cellpadding=2 cellspacing=1 border=0 width="100%">
		<tr class=TableHead bgcolor=#ffffff>
    		<th width=20>Pos.</th>
    		<th>Title</th>
    		<th width=20>Delete</th>
		</tr>
		<tbody bgcolor=#ffffff FOREACH="product.RelatedProducts,RP">
		<tr>
			<td align=center width=20><input type="text" size="4" name="updates_product_ids[{RP.product.product_id}]" value="{RP.order_by}"></td>
			<td nowrap>
				<a href="admin.php?target=product&product_id={RP.product.product_id}">{RP.product.name:h}</a>
				<font IF="{!RP.product.enabled}" color=red>&nbsp;&nbsp;&nbsp;(not available for sale)</font>
				<widget module="ProductAdviser" template="modules/ProductAdviser/product_list.tpl" product="{RP.product}">
			</td>
			<td align=center><input type="checkbox" name="delete_product_ids[{RP.product.product_id}]" id="related_product_{RP.product.product_id}" onClick="this.blur()" onChange="RPCheckBoxChanged(this)"></td>
    		<script language="Javascript">related_products[related_products.length] = "related_product_{RP.product.product_id}";</script>
		</tr>
		</tbody>
		</table>
	</td>
</tr>
<tr>
	<td>
		<br>
        <table border=0 cellpadding=3 cellspacing=0 width=100%>
        <tr>
    		<td><input type="submit" value=" Update "></td>
    		<td align=right width=100%>
                <table border=0 cellpadding=3 cellspacing=0 align=right>
                <tr>
                    <td>
                    <b>
                    <a href="javascript:CheckAll()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Check All</a>
                    &nbsp;&nbsp;
                    <a href="javascript:UncheckAll()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Uncheck All</a>
                    </b>
                    </td>
            		<td>&nbsp;&nbsp;</td>
            		<td><input type="button" value=" Delete " onClick="ProcessDelete()"></td>
                </tr>
                </table>
    		</td>
        </tr>
        </table>

	</td>
</tr>
</form>
</table>
</span>
	
<br><br>
<font class=AdminTitle>Add related products</font>
<br><br>
<widget template="product/search.tpl">

<span IF="mode=#search#">
<span IF="products">
<a name="productsList"></a>
</span>

<table border=0 cellpadding=0 cellspacing=0 width=400>
<tr>
	<td>{productsFound} product(s) found.<br><br></td>
</tr>

<tbody IF="products">

<script language="Javascript">
<!--

var new_products = new Array(); 

function ProcessAllNew(status)
{
    for (var i=0; i<new_products.length; i++) {
    	var Element = document.getElementById(new_products[i]);
    	if (Element) {
        	Element.checked = status;
        }
    }
}

function CheckAllNew()
{
	ProcessAllNew(true);
}

function UncheckAllNew()
{
	ProcessAllNew(false);
}

-->
</script>

<form action="admin.php" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="add_related_products">
<tr>
	<td bgcolor=#dddddd>
		<table cellpadding=2 cellspacing=1 border=0 width="100%">
		<tr class=TableHead bgcolor=#ffffff>
    		<th>Add</th><th>Title</th>
		</tr>
		<tbody bgcolor=#ffffff FOREACH="products,product">
		{if:isArrayPointerEven(productArrayPointer)}
		<tr bgcolor=#eeeeff>
		{else:}
		<tr bgcolor=#ffffff>
		{end:}
			<td align=center><input type="checkbox" name="product_ids[{product.product_id}]" value="1" id="new_product_{product.product_id}" onClick="this.blur()"></td>
			<td>{product.name:h}<widget module="ProductAdviser" template="modules/ProductAdviser/product_list.tpl" product="{product}"></td>
    		<script language="Javascript">new_products[new_products.length] = "new_product_{product.product_id}";</script>
		</tr>
		</tbody>
		</table>
	</td>
</tr>
<tr>
	<td>
		<br>
        <table border=0 cellpadding=3 cellspacing=0>
        <tr>
    		<td><input type="submit" value=" Add "></td>
    		<td>&nbsp;&nbsp;</td>
    		<td>
            <b>
            <a href="javascript:CheckAllNew()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Check All</a>
            &nbsp;&nbsp;
            <a href="javascript:UncheckAllNew()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Uncheck All</a>
            </b>
    		</td>
        </tr>
        </table>
	</td>
</tr>
</form>
</tbody>
</table>

<script language="Javascript">window.location="#productsList";</script>

</span>
