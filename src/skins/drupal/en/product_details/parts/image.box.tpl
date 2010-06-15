{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details image default box
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="productDetails.image", weight="10")
 *}
<div class="product-thumbnail-box">
  <widget class="XLite_View_Image" image="{product.getImage()}" className="photo product-thumbnail" id="product_image_{product.product_id}" maxWidth="100" centerImage />
  <widget class="XLite_View_SaveMark" product="{product}" />
</div>
