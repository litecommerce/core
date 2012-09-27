{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Custom javascript
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.details.page", weight="1000")
 *}
<script IF="product.getJavascript()" type="text/javascript">
$(document).ready(
  function() {
    $('form[name="add_to_cart"]').submit(
      function() {
        {product.getJavascript()}
      }
    );
  }
);
</script>
