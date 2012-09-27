{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product thumbnail zoom
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div class="product-photo" style="height:{getWidgetMaxHeight()}px; width:{getWidgetMaxWidth()}px">
  <a href="{getZoomImageURL()}" class="cloud-zoom" id="pimage_{product.product_id}" rel="adjustX: {getZoomAdjustX()}, showTitle: false, tintOpacity: 0.5, tint: '#fff', lensOpacity: 0" title="{t(#Thumbnail#)}">
    <widget class="\XLite\View\Image" image="{product.getImage()}" className="photo product-thumbnail" id="product_image_{product.product_id}" maxWidth="{getWidgetMaxWidth()}" maxHeight="{getWidgetMaxHeight()}" alt="{t(#Thumbnail#)}" />
    {displayCommentedData(getJSData())}
  </a>
</div>
