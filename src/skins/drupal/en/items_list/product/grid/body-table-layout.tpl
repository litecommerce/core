{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (grid variant)
 * NOTE: Unfortunately TABLE layout is the only cross-browser way to line up buttons
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

{displayViewListContent(#itemsList.product.cart#)}

<!--
<table class="list-body list-body-grid list-body-grid-{getParam(#gridColumns#)}-columns" IF="!isCSSLayout()">
-->
<div class="{getContainerClass()}">

  <table class="products-grid grid-{getParam(#gridColumns#)}-columns">
    {foreach:getProductRows(),row}
      <tr>
        {foreach:row,idx,product}
        <td IF="product" class="product-cell">
          <div class="product productid-{product.getProductId()}">
            {displayListPart(#info#,_ARRAY_(#product#^product))}
          </div>
        </td>
        <td IF="!product">&nbsp;</td>
        {end:}
      </tr>
      {end:}

      {displayListPart(#items#)}

    </tbody>
  </table>

  <div IF="isShowMoreLink()">
    <a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a>
  </div>

</div>
