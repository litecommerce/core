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
<table class="list-body list-body-grid list-body-grid-{getParam(#gridColumns#)}-columns" IF="!isCSSLayout()">

  <tbody FOREACH="getProductRows(),row">

    <tr class="info">
      <td FOREACH="row,product" class="hproduct"><div IF="product">{displayListPart(#title#,_ARRAY_(#product#^product))}</div></td>
    </tr>

    <tr class="buttons">
      <td FOREACH="row,product" class="product"><div IF="product">{displayListPart(#info#,_ARRAY_(#product#^product))}</div></td>
    </tr>

  </tbody>

  {displayListPart(#items#)}

</table>

{* Use a CSS layout *}
{* FIXME - must be revised *}
<ul IF="isCSSLayout()" class="list-body list-body-grid">
  <li FOREACH="getPageData(),product" class="item">
    {* FF2 requires an extra div in order to display "inner-blocks" properly *}
    <div>
      {displayViewListContent(#productsList.gridItem.title#,_ARRAY_(#product#^product))}
      {displayViewListContent(#productsList.gridItem.info#,_ARRAY_(#product#^product))}
    </div>
  </li>
  <li FOREACH="getViewList(#productsList.gridItems#),w">
    {w.display()}
  </li>
</ul>

<div IF="isShowMoreLink()">
  <a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a>
</div>
