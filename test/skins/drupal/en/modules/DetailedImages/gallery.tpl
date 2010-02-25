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
    <a href="{image.imageURL}"><img src="{image.imageURL}" alt="{image.alt:r}" /></a>
  </li>
</ul>
<a href="javascript:void(0);" onclick="javascript: $('.di-gallery a').eq(0).trigger('click')">See all images ({product.getDetailedImagesCount()})</a>
<script type="text/javascript">
var lightBoxImagesDir = '{lightBoxImagesDir}';
$(
  function() {
    $('.di-gallery a').lightBox(
      {
        fixedNavigation: true,
        imageLoading:    lightBoxImagesDir + '/lightbox-ico-loading.gif',
        imageBtnPrev:    lightBoxImagesDir + '/lightbox-btn-prev.gif',
        imageBtnNext:    lightBoxImagesDir + '/lightbox-btn-next.gif',
        imageBtnClose:   lightBoxImagesDir + '/lightbox-btn-close.gif',
        imageBlank:      lightBoxImagesDir + '/lightbox-blank.gif'
      }
    );
  }
);
</script>
