{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<script language="Javascript">
<!-- 

function setChecked(form, input, check)
{
    var elements = document.forms[form].elements[input];

    for (var i = 0; i < elements.length; i++) {
        elements[i].checked = check;
    }
}

function setHeaderChecked()
{
	var Element = document.getElementById("activate_products");
    if (Element && !Element.checked) {
    	Element.checked = true;
    }
}

// -->
</script>

<p class="SuccessMessage" IF="status=#updated#">&gt;&gt;&nbsp;Product information has been updated successfully.&nbsp;&lt;&lt;</p>

<p class="SuccessMessage" IF="status=#deleted#">&gt;&gt;&nbsp;Products have been deleted successfully.&nbsp;&lt;&lt;</p>

<p class="SuccessMessage" IF="status=#cloned#">&gt;&gt;&nbsp;Products have been cloned successfully.&nbsp;&lt;&lt;</p>

<widget class="XLite_View_Pager" data="{products}" name="pager" itemsPerPage="{config.General.products_per_page_admin}" />

<br />

<form name="products_form" action="admin.php" method="POST">

  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}" />
  <input type="hidden" name="action" value="update" />

  <table border=0>

    <tr class="TableHead">
      <th><input id="activate_products" type="checkbox" onClick="this.blur();setChecked('products_form','product_ids',this.checked);" /></th>
      <th>SKU</th>
      <th align=left>Product Name</th>
      <th align=center IF="xlite.mm.activeModules.WholesaleTrading">Prod.#</th>
      <th>Category</th>
      <th>Pos.</th>
      <th nowrap>Price</th>
    </tr>

    <tbody FOREACH="namedWidgets.pager.pageData,product_idx,product" class="{getRowClass(product_idx,##,#TableRow#)}">

      <tr>
        <td width=1%>
		      <input id="product_ids" type="checkbox" name="product_ids[]" value="{product.product_id}" onClick="this.blur()" />
        </td>
        <td width=1%>{product.sku}</td>
        <td width=99%>
          <a href="admin.php?target=product&product_id={product.product_id}&backUrl={url:u}"><font class="ItemsList"><u>{product.name:h}</u></font></a><widget module="ProductAdviser" template="modules/ProductAdviser/product_list.tpl" product="{product}" />
        </td>
        <td width=1% align=right IF="xlite.mm.activeModules.WholesaleTrading"><a href="admin.php?target=product&product_id={product.product_id}&backUrl={url:u}"><u>#{product.product_id:h}</u></a></td>
        <td nowrap>
        {if:xlite.mm.activeModules.MultiCategories}
        	{foreach:product.categories,cat}
          	{if:!catArraySize=#1#}&#8226;{else:}&nbsp;{end:}&nbsp;{cat.stringPath}&nbsp;<br>
         	{end:}
        {else:}
          &nbsp;{product.category.stringPath}&nbsp;
        {end:}
        </td>
        <td nowrap align="right">
          <input type="text" size=4 maxlength=4 value="{product.order_by}" name="product_orderby[{product.product_id}]" />
        </td>
        <td nowrap align=right>
          <input type="text" size="7" value="{product.price}" name="product_price[{product.product_id}]" />
        </td>
      </tr>

      <widget module="ProductAdviser" template="modules/ProductAdviser/price_list_changed.tpl" visible="{isNotifyPresent(product.product_id)}" dialog="{dialog}" product="{product}" />

    </tbody>

    <tr>
      <td colspan=6>&nbsp;</td>
    </tr>

    <tr>
    	<td colspan=4>
        <input type="button" value=" Clone selected " onClick="products_form.action.value='clone'; products_form.submit()" />&nbsp;&nbsp;
        <input type="button" value=" Delete selected " onClick="products_form.action.value='delete'; products_form.submit()" />&nbsp;&nbsp;
    	</td>
      <td colspan=2>
        <input type="button" value=" Update " onClick="products_form.action.value='update'; products_form.submit()" class="DialogMainButton" />&nbsp;&nbsp;
      </td>
    </tr>

  </table>

</form>

