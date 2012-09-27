{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details image box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.details.page.image.photo", weight="15")
 *}
<a IF="product.getImages()&!product.countImages()=1" class="arrow right-arrow" href="javascript:void(0);"><img src="images/spacer.gif" alt="Next image" /></a>
<span IF="!product.getImages()|product.countImages()=1" class="arrow right-arrow"></span>
