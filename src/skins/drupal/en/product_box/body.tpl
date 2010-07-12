{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul class="list-body list-body-grid">
  <li class="item">
    <span class="draggable-mark">Drag me to the cart</span>
    <a IF="product.hasThumbnail()" class="product-thumbnail" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}"><img src="{product.getThumbnailURL()}" alt="{product.name}" /></a>
    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" class="product-name">{product.name:h}</a>
    <widget class="\XLite\View\Price" product="{product}" displayOnlyPrice="true" />
    <widget class="\XLite\View\BuyNow" product="{product}" style="aux-button add-to-cart" />
  </li>
</ul>
