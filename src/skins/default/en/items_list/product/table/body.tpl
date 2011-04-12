{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (table variant)
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<div class="products">

  <table class="products-table" cellspacing="0" IF="getPageData()">
    <tr>
      {displayInheritedViewListContent(#captions#)}
    </tr>
    <tr FOREACH="getPageData(),product" class="product-cell {getProductCellClass(product)}">
      <td FOREACH="getInheritedViewList(#columns#,_ARRAY_(#product#^product)),column">{column.display()}</td>
    </tr>
  </table>

  {displayNestedViewListContent(#buttons#)}

</div>
