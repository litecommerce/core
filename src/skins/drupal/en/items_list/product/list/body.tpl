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
<ul class="list-body list-body-list">

  <li FOREACH="getPageData(),product" class="hproduct item">
    {displayListPart(#body#,_ARRAY_(#product#^product))}
  </li>

  <li FOREACH="getViewList(#itemsList.product.customer.list.items#),w" class="item additional-item">
    {w.display()}
  </li>

  <li IF="isShowMoreLink()">
    <a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a>
  </li>

</ul>
