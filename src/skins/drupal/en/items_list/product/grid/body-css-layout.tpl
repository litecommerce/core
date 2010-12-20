{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (grid variant)
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

  <ul class="products-grid grid-list" IF="getPageData()">
    <li FOREACH="getPageData(),product" class="product-cell hproduct">
      <div class="{getProductCellClass(product)}">
        {displayListPart(#info#,_ARRAY_(#product#^product))}
      </div>
    </li>
    <li FOREACH="getNestedViewList(#items#),item" class="product-cell hproduct">{item.display()}</li>
  </ul>

</div>
