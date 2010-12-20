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

  <table class="products-grid grid-{getParam(#gridColumns#)}-columns">
    {foreach:getProductRows(),row}
      <tr>
        {foreach:row,idx,product}
          <td IF="product" class="product-cell hproduct">
            <div class="{getProductCellClass(product)}">
              {displayListPart(#info#,_ARRAY_(#product#^product))}
            </div>
          </td>
          <td IF="!product">&nbsp;</td>
        {end:}
      </tr>
    {end:}

  </table>

  <div IF="isShowMoreLink()">
    <a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a>
  </div>

</div>
