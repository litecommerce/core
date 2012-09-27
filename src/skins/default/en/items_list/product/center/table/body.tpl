{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (table variant)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div class="products">

  <table class="products-table" cellspacing="0" IF="getPageData()">
    <tr>
      <list name="captions" type="inherited" />
    </tr>
    <tr FOREACH="getPageData(),product" class="product-cell {getProductCellClass(product)}">
      <td FOREACH="getInheritedViewList(#columns#,_ARRAY_(#product#^product)),column">{column.display()}</td>
    </tr>
  </table>

  <list name="buttons" type="nested" />

</div>
