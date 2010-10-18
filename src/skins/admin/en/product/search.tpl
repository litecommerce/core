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

<widget class="\XLite\View\Form\Product\Search\Admin\Main" name="search_form" />

  <table border="0">

    	<tr>
    		<td class="FormButton" nowrap="nowrap" height="10">Product SKU</td>
    		<td width="10" height="10"></td>
    		<td height="10"><input size="6" name="sku" value="{getCondition(#sku#):r}"></td>
    	</tr>

    	<tr>
    		<td class="FormButton" nowrap="nowrap" height="10">Product Title</td>
    		<td width="10" height="10"></td>
    		<td height="10"><input size="30" name="substring" value="{getCondition(#substring#):r}"></td>
    	</tr>

    	<tr>
    		<td class="FormButton" nowrap="nowrap" height="10">In category</td>
    		<td width="10" height="10"><font class="ErrorMessage">*</font></td>
    		<td height="10">
          <widget class="\XLite\View\CategorySelect" fieldName="categoryId" selectedCategoryId="{getCondition(#categoryId#):r}" allOption />
        </td>
  	  </tr>

    	<tr>
    		<td class="FormButton" nowrap="nowrap" height="10" colspan="3">
    			Search in subcategories
    			<input type="checkbox" name="searchInSubcats" checked="{getCondition(#searchInSubcats#)|!mode=#search#}" value="1">
    		</td>
      </tr>

      <widget module="ProductAdviser" template="modules/ProductAdviser/product_search.tpl">

      <widget module="InventoryTracking" template="modules/InventoryTracking/product_search.tpl">

      {displayViewListContent(#product.search.conditions#)}

      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>

    	<tr>
    		<td colspan="3"><widget class="\XLite\View\Button\Submit" label="Search" /></td>
      </tr>

  </table>

<widget name="search_form" end />

<br />

<b>Note:</b> You can also <a href="admin.php?target=product"><u>add a new product</u></a>.

