{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details image box
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="product.details.page.image.photo", weight="5")
 *}
<a IF="product.getActiveDetailedImages()" class="arrow left-arrow" href="javascript:void(0);"><img src="images/spacer.gif" alt="Previous image" /></a>
<span IF="!product.getActiveDetailedImages()" class="arrow left-arrow"></span>
