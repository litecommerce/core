{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product thumbnail zoom
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="image-box">
  <a IF="product.getActiveDetailedImages()" class="arrow left-arrow" href="javascript:void(0);"><img src="images/spacer.gif" alt="" /></a>
  <div class="image-center">
    <a href="{getZoomImageURL()}" class="cloud-zoom" id="pimage_{product.product_id}" rel="adjustX: 110, showTitle: false">
      <widget class="\XLite\View\Img" image="{product.getImage()}" className="photo product-thumbnail" id="product_image_{product.product_id}" maxWidth="350" />
      <widget class="\XLite\View\SaveMark" product="{product}" />
    </a>
  </div>
  <a IF="product.getActiveDetailedImages()" class="arrow right-arrow" href="javascript:void(0);"><img src="images/spacer.gif" alt="" /></a>
</div>
