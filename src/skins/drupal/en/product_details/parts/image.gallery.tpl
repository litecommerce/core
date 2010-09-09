{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Gallery widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<a href="javascript:void(0);" class="loupe"><img src="images/spacer.gif" alt="" /></a>

<div class="gallery-container">
  <ul class="gallery">
    <li FOREACH="product.getActiveDetailedImages(),i,image" class="{getListItemClass(i)}">
      <a href="{image.getFrontURL()}" rel="gallery" rev="width: {image.getWidth()}, height: {image.getHeight()}" title="{image.getAlt()}"><widget class="\XLite\View\Img" image="{image}" alt="{image.getAlt()}" maxWidth="60" maxHeight="60" /></a>
      <widget class="\XLite\View\Img" className="middle" style="display: none;" image="{image}" maxWidth="350" />
    </li>
  </ul>
</div>

<script type="text/javascript">
var lightBoxImagesDir = '{getLightBoxImagesDir()}';
</script>
