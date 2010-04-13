{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (sidebar variant)
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul class="list-body-sidebar">

  <li class="item" FOREACH="getSideBarData(),product">

    <a IF="isShowThumbnails()&product.hasThumbnail()" class="product-thumbnail" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}"><widget class="XLite_View_Image" image="{product.getThumbnail()}" maxWidth="45" maxHeight="45" alt="{product.name}" /></a>

    <div class="body">

        <a class="product-name" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}">{product.name:h}</a>
        <br />

{*        <widget class="XLite_View_Price" product="{product}" displayOnlyPrice="true" IF="isShowPrice()" /> *}
        <widget class="XLite_View_BuyNow" product="{product}" IF="isShowAdd2Cart()" style="aux-button add-to-cart" showPrice="{isShowPrice()}" />

    </div>

  </li>

  <li IF="isShowMoreLink()">
    <a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a>
  </li>

</ul>
