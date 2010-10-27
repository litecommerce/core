{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (table variant)
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="products">

  <table class="products-table" IF="getPageData()">
    <tr>
      {displayListPart(#captions#)}
    </tr>
    <tr FOREACH="getPageData(),product" class="product-cell {getProductCellClass(product)}">
      <td FOREACH="getNestedViewList(#columns#,_ARRAY_(#product#^product)),column">{column.display()}</td>
    </tr>
  </table>

  {displayListPart(#buttons#)}

</div>
