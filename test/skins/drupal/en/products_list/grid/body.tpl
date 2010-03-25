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
<ul class="list-body list-body-grid list-body-grid-{gridColumns}-columns">
  <li FOREACH="getPageData(),product" class="item">
    {* FF2 requires an extra div in order to display "inner-blocks" properly *}
    <div>
      <widget class="XLite_View_AddedToCartMark" product="{product}" />
      <span class="draggable-mark">Drag me to the cart</span>
      <a IF="isShowThumbnails()&product.hasThumbnail()" class="product-thumbnail" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}"><img src="{product.getThumbnailURL()}" alt="{product.name}" /></a>
      <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" class="product-name">{product.name:h}</a>
      <widget class="XLite_View_Price" product="{product}" displayOnlyPrice="true" IF="isShowPrice()" />
      <widget class="XLite_View_BuyNow" product="{product}" IF="isShowAdd2Cart()" style="aux-button add-to-cart" />
    </div>
  </li>
</ul>

<div IF="isShowMoreLink()">
  <a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a>
</div>
