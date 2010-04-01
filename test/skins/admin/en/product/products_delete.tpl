{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products delete confirmation template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<form name="deleteForm" action="admin.php" method="POST">

  <input FOREACH="getAllparams(#mode#),_name,_val" type="hidden" name="{_name}" value="{_val:r}" />
  <input type="hidden" name="target" value="product_list" />
  <input type="hidden" name="action" value="delete" />
  <input type="hidden" name="confirmed" value="0" />

  <table border="0">

    <tr>
      <td colspan="3">All the following products will be deleted:</td>
    </tr>

    <tr>
      <td colspan="3">

        <table border=0 cellpadding="2" cellspacing="2">

          <tr class="TableHead">
            <th>SKU</th>
            <th align=left>Product Name</th>
            <th>Category</th>
            <th nowrap>Price</th>
          </tr>

          <tbody FOREACH="product_ids,product_idx,product" class="{getRowClass(product_idx,##,#TableRow#)}">

            <tr>
              <td width=1%>{product.sku}</td>
              <td width=99%><font class="ItemsList">{product.name:h}</font></td>
              <td nowrap>
              {if:xlite.mm.activeModules.MultiCategories}
              	{foreach:product.categories,cat}
                	{if:!catArraySize=#1#}&#8226;{else:}&nbsp;{end:}&nbsp;{cat.stringPath}&nbsp;<br />
              	{end:}
              {else:}
                &nbsp;{product.category.stringPath}&nbsp;
              {end:}
              </td>
              <td nowrap align=right>
              	{price_format(product,#price#):h}
        				<input type="hidden" name="product_ids[]" value="{product.product_id}" />
              </td>
            </tr>

          </tbody>

        </table>

    	</td>
    </tr>

    <tr>
      <td colspan=3>&nbsp;</td>
    </tr>

    <tr>
      <td colspan="3" class="AdminTitle">Warning: this operation can not be reverted!</td>
    </tr>

    <tr>
      <td colspan=3>&nbsp;</td>
    </tr>

    <tr>	
      <td colspan=3>Are you sure you want to continue?<br /><br />
        <input type="button" value="Yes" class="DialogMainButton" onClick="javascript: document.deleteForm.confirmed.value='1'; document.deleteForm.submit();" />
        &nbsp;&nbsp;
        <input type="submit" value="No" />
      </td>
    </tr>

  </table>

</form>

