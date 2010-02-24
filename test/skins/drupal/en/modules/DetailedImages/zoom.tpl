{* SVN $Id$ *}
<a href="{product.zoomImage.imageURL}" id="product_image_{product.product_id}_link"><img src="{product.imageURL}" id="product_image_{product.product_id}" alt="" /></a>
<script type="text/javascript">
$(document).ready(
  function() {
    $('#product_image_{product.product_id}_link').jqzoom(
      {
        title: false,
        showPreload: false
      }
    );
  }
);
</script>
