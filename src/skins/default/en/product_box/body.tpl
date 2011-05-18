{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<ul class="list-body list-body-grid">

  <li class="item">

    <span class="draggable-mark">{t(#Drag me to the cart#)}</span>

    <a IF="product.hasImage()" class="product-thumbnail" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}">
      <widget class="\XLite\View\Image" image="{product.getImage()}" maxWidth="{getIconWidth()}" maxHeight="{getIconHeight()}" alt="{product.name}" className="photo" />
    </a>
    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" class="product-name">{product.name:h}</a>

    <widget class="\XLite\View\Price" product="{product}" displayOnlyPrice="true" />

    <widget IF="{isBuyNowVisible()}" class="\XLite\View\BuyNow" product="{product}" style="aux-button add-to-cart" />

  </li>

</ul>
