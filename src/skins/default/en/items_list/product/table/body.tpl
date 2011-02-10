{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (table variant)
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table class="list-body list-body-table" cellspacing="0">

  <tr FOREACH="getPageData(),product" class="hproduct item">
    {displayNestedViewListContent(#info#,_ARRAY_(#product#^product))}
  </tr>

  <tr FOREACH="getViewList(#itemsList.product.table.customer.items#),w">
    {w.display()}
  </tr>

  <tr IF="isShowMoreLink()">
    <td colspan="{getTableColumnsCount()}"><a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a></td>
  </tr>

</table>
