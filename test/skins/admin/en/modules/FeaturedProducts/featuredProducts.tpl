{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Feature products management template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<form action="admin.php" method="POST" IF="category.featuredProducts">

  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}" />
  <input type="hidden" name="action" value="update_featured_products" />

  <table border=0 cellpadding=0 cellspacing=0 width="80%">

    <tr>
      <td bgcolor=#dddddd>

        <table cellpadding=2 cellspacing=1 border=0 width="100%">

          <tr class=TableHead bgcolor=#ffffff>
            <th width=20>Delete</th>
            <th width=20>Pos.</th>
            <th>Title</th>
          </tr>

          <tr bgcolor=#ffffff FOREACH="category.featuredProducts,featuredProduct">
            <td align=center width=20><input type="checkbox" name="delete[{featuredProduct.product.product_id}]" /></td>
            <td align=center width=20><input type="text" size="4" name="orderbys[{featuredProduct.product.product_id}]" value="{featuredProduct.order_by}" /></td>
            <td nowrap>
              <a href="admin.php?target=product&product_id={featuredProduct.product.product_id}">{featuredProduct.product.name:h}</a>
              <font IF="{!featuredProduct.product.enabled}" color=red>&nbsp;&nbsp;&nbsp;(not available for sale)</font>
            </td>
          </tr>

        </table>

      </td>
    </tr>

  </table>

  <br />

  <input type="submit" value=" Update ">

</form>

<br /><br />

<font class=AdminTitle>Add featured products</font>

<br /><br />

<widget template="product/search.tpl" />

<br /><br />

<form action="admin.php" method="POST" IF="products">

  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}" />
  <input type="hidden" name="action" value="add_featured_products" />

  <table border=0 cellpadding=0 cellspacing=0 width=400>

    <tr>

      <td bgcolor=#dddddd>

        <table cellpadding=2 cellspacing=1 border=0 width="100%">

          <tr class=TableHead bgcolor=#ffffff>
            <th>Add</th>
            <th>Title</th>
          </tr>

          <tr bgcolor=#ffffff FOREACH="products,product">
            <td align=center><input type="checkbox" name="product_ids[{product.product_id}]" /></td>
            <td>{product.name:h}</td>
          </tr>

        </table>

      </td>
    </tr>

  </table>

  <br />

  <input type="submit" value=" Add " />

</form>

<span IF="getRequestParamValue(#mode#)=#search#">
  <br /><br />
  {productsFound} product(s) found
</span>
