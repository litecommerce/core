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
<ul class="di-gallery">
  <li FOREACH="product.getDetailedImages(),image">
    <div><a href="{image.imageURL}"><widget class="XLite_Module_DetailedImages_View_Image" image="{image.image}" maxWidth="50" maxHeight="40" /></a></div>
  </li>
</ul>
<a href="javascript:void(0);" onclick="javascript: $('.di-gallery a').eq(0).trigger('click')">See all images ({product.getDetailedImagesCount()})</a>
<script type="text/javascript">
var lightBoxImagesDir = '{getLightBoxImagesDir()}';
</script>
