{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product search form template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<form name="search_form" action="admin.php" method="GET">

{if:extraParams=##}
  <input FOREACH="allParams,key,val" type="hidden" name="{key}" value="{val:r}" />
{else:}
  <input FOREACH="extraParams,key,val" type="hidden" name="{key}" value="{val:r}" />
{end:}

  <input type="hidden" name="mode" value="search" />

{extra_parameters:h}

  <table border=0>

    <tbody>

    	<tr>
    		<td class="FormButton" noWrap height=10>Product SKU</td>
    		<td width=10 height=10></td>
    		<td height=10><input size=6 name="search_productsku" value="{search_productsku}"></td>
    	</tr>

    	<tr>
    		<td class="FormButton" noWrap height=10>Product Title</td>
    		<td width=10 height=10></td>
    		<td height=10><input size=30 name="substring" value="{substring}"></td>
    	</tr>

    	<tr>
    		<td class="FormButton" noWrap height=10>In category</td>
    		<td width=10 height=10><font class="ErrorMessage">*</font></td>
    		<td height=10>
          <widget class="XLite_View_CategorySelect" fieldName="search_category" selectedCategoryId="{search_category}" allOption />
        </td>
  	  </tr>

    	<tr>
    		<td class="FormButton" noWrap height=10 colspan="3">
    			Search in subcategories
    			<input type="checkbox" name="subcategory_search" checked="{subcategory_search}">
    		</td>
      </tr>

      <widget module="ProductAdviser" template="modules/ProductAdviser/product_search.tpl">

      <widget module="InventoryTracking" template="modules/InventoryTracking/product_search.tpl">

      <tr>
        <td colspan=3>&nbsp;</td>
      </tr>

    	<tr>
    		<td colspan=3><input type="submit" value=" Search " /></td>
      </tr>

    </tbody>

  </table>

</form>

<br />

<b>Note:</b> You can also <a href="admin.php?target=add_product"><u>add a new product</u></a>.

