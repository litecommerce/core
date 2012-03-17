{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<div class="orders-list {getClassIdentifier()}">

  <list name="orders.children" />

{* TODO Restore

<script type="text/javascript">
jQuery(document).ready(
  function() {
    jQuery('.orders-list.{getClassIdentifier()}').each(
      function() {
        new OrdersListController(this, {getAJAXRequestParamsAsJSObject():r});
      }
    );
  }
);
</script>
*}

</div>
