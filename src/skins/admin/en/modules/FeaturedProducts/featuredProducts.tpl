{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Featured products management template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<form method="post" action="admin.php" IF="featuredProductsList">

  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}" />
  <input type="hidden" name="action" value="update_featured_products" />

  <table border="0" cellpadding="0" cellspacing="0" width="100%">

    <tr>
      <td bgcolor="#dddddd">

        <table cellpadding="2" cellspacing="1" border="0" width="100%">

          <tr class="TableHead" bgcolor="#ffffff">
            <th width="2"0>Delete</th>
            <th width="2"0>Pos.</th>
            <th>Title</th>
          </tr>

          <tr bgcolor="#ffffff" FOREACH="featuredProductsList,featuredProduct">
            <td align="center" width="2"0><input type="checkbox" name="delete[{featuredProduct.id}]" /></td>
            <td align="center" width="2"0><input type="text" size="4" name="orderbys[{featuredProduct.id}]" value="{featuredProduct.order_by}" /></td>
            <td nowrap>
              <a href="admin.php?target=product&product_id={featuredProduct.product.product_id}">{featuredProduct.product.name:h}</a>
              <font IF="{!featuredProduct.product.enabled}" color="red">&nbsp;&nbsp;&nbsp;(not available for sale)</font>
            </td>
          </tr>

        </table>

      </td>
    </tr>

  </table>

  <br />

  <widget class="\XLite\View\Button\Submit" label="Update" />

</form>

<br /><br />

<font class="AdminTitle">Add featured products</font>

<br /><br />

<form method="post" action="admin.php">
  
  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}" />
  <input type="hidden" name="action" value="search_featured_products" />

  <table border="0" cellpadding="3" cellspacing="1">

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
          <input type="checkbox" name="searchInSubcats" checked="{getCondition(#searchInSubcats#)|!mode=#search_featured_products#}" value="1">
        </td>
      </tr>

      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="3"><widget class="\XLite\View\Button\Submit" label="Search" /></td>
      </tr>

  </table>

</form>

<br /><br />

<form method="post" IF="featuredSearchResult">

  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}" />
  <input type="hidden" name="action" value="add_featured_products" />

  <table border="0" cellpadding="0" cellspacing="0" width="100%">

    <tr>

      <td bgcolor="#dddddd">

        <table cellpadding="2" cellspacing="1" border="0" width="100%">

          <tr class="TableHead" bgcolor="#ffffff">
            <th>Add</th>
            <th>SKU</th>
            <th>Title</th>
          </tr>

          <tr bgcolor="#ffffff" FOREACH="featuredSearchResult,product">
            <td align="center"><input type="checkbox" name="product_ids[{product.product_id}]" /></td>
            <td>{product.sku}</td>
            <td>{product.name:h}</td>
          </tr>

        </table>

      </td>
    </tr>

  </table>

  <br />

  <widget class="\XLite\View\Button\Submit" label="Add" />

</form>

<span IF="getRequestParamValue(#mode#)=#search_featured_products#">
  <br /><br />
  {featuredSearchResultCount} product(s) found
</span>
