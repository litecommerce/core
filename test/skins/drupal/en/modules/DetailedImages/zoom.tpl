{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product thumbnail zoom
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<a href="{product.zoomImage.imageURL}" class="product-thumbnail">
  <img src="{product.imageURL}" class="product-thumbnail" id="product_image_{product.product_id}" alt="" />
  <widget class="XLite_View_SaveMark" product="{product}" />
</a>
<script type="text/javascript">
$(document).ready(
  function() {
    $('#product_image_{product.product_id}').parents('a').eq(0).jqzoom(
      {
        title: false,
        showPreload: false
      }
    );
  }
);
</script>
