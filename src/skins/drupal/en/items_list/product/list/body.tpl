{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (list variant)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

{displayViewListContent(#itemsList.product.cart#)}

<div class="products">

  <ul class="products-list" IF="getPageData()">
    <li FOREACH="getPageData(),product" class="product-cell">
      {*
       * Unfortunately, here we can use neither regular ul/li (because we don't know the image width),
       * nor table-cell (becase it is not supported by IE6/IE7),
       * nor a single table with border-spacing and negative margins (because we need borders around two cells)
       *}
      <table class="{getProductCellClass(product)}">
        <tr>
          {*
           * Since there is no way to make a TD having a relative position, we need a container
           * in order to position Absolute and Relative elements in nested lists 
           *}
          <td class="product-photo"><div class="container">{displayListPart(#photo#,_ARRAY_(#product#^product))}</div></td>
          <td class="product-info"><div class="container">{displayListPart(#info#,_ARRAY_(#product#^product))}</div></td>
        </tr>
      </table>
    </li>
  </ul>

</div>
