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

<p />

<table class="list-body list-body-grid list-body-grid-{getParam(#gridColumns#)}-columns" IF="!isCSSLayout()">

  <tbody FOREACH="getProductRows(),row">
  <tr class="info">
    {foreach:row,idx,product}
    <td IF="!idx=#0#" class="separator"></td>
    <td IF="product" class="hproduct" id="{product.getProductId()}">
      <div class="quick-look-cell">
        {displayListPart(#quick_look.info#)}
        {displayListPart(#info#,_ARRAY_(#product#^product))}
      </div>
    </td>
    <td IF="!product">&nbsp;</td>
    {end:}
  </tr>
  <tr>
    <td colspan="100" class="separator"></td>
  </tr>
  </tbody>

  {displayListPart(#items#)}

</table>

<div IF="isShowMoreLink()">
  <a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a>
</div>
