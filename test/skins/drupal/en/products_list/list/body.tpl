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

  <li FOREACH="getPageData(),product" class="item">
    <widget class="XLite_View_AddedToCartMark" product="{product}" />
    <span class="draggable-mark">Drag me to the cart</span>
    <a IF="isShowThumbnails()" class="product-thumbnail" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}"><widget class="XLite_View_Image" image="{product.getThumbnail()}" centerImage=0 maxWidth="{getIconWidth()}" maxHeight="{getIconHeight()}" alt="{product.name}" /></a>
    <div class="body">
      <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" class="product-name">{product.name:h}</a>
      <br />
      <div IF="isShowDescription()" class="product-description">{truncate(product,#brief_description#,#300#):h}</div>
      <widget class="XLite_View_Price" product="{product}" displayOnlyPrice="true" IF="isShowPrice()" />
      <widget class="XLite_View_BuyNow" product="{product}" IF="isShowAdd2Cart(product)" style="aux-button add-to-cart" />
    </div>

  </li>

  <li IF="isShowMoreLink()">
    <a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a>
  </li>

</ul>
