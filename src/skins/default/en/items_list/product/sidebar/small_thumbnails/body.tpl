{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (sidebar variant)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<list name="itemsList.product.cart" />

<ul class="list-body-sidebar products products-sidebar products-sidebar-small-thumbnails">

  <li FOREACH="getSideBarData(),i,product" class="product-cell hproduct item {getAdditionalItemClass(productArrayPointer,productArraySize)}">
    <div class="{getProductCellClass(product)}">
      <list name="info" type="inherited" product="{product}" />
      <div class="clear"></div>
    </div>
  </li>

  <li IF="isShowMoreLink()">
    <a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a>
  </li>

</ul>
