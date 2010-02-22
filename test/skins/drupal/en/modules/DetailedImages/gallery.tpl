{* SVN $Id$ *}
<ul class="di-gallery">
  <li FOREACH="product.detailedImages,image">
    <a href="{image.imageURL}"><img src="{image.imageURL}" alt="{image.alt:r}" /></a>
  </li>
</ul>
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
