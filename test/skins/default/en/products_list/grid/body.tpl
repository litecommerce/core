{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products grid template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul class="list-body list-body-grid">

  <li FOREACH="getData(),product" class="item" style="width: {getGridItemWidth()}%;">

    <div>
      <widget class="XLite_View_AddedToCartMark" product="{product}" />
      <a IF="config.General.show_thumbnails&product.hasThumbnail()" class="product-thumbnail" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}"><img src="{product.thumbnailURL}" alt="" /></a>
      <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" class="product-name">{product.name:h}</a>
      <widget class="XLite_View_Price" product="{product}" displayOnlyPrice="true" />
      <widget class="XLite_View_BuyNow" product="{product}" />
    </div>

  </li>

</ul>
