{* SVN $Id$ *}
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
