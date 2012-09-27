{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Image loupe
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.details.page.image", weight="15")
 *}

<a IF="product.getImages()&!product.countImages()=1" href="javascript:void(0);" class="loupe"><img src="images/spacer.gif" alt="Zoom image" /></a>
