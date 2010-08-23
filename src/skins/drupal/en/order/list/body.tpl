{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="orders-list {getClassIdentifier()}">
  {displayViewListContent(#orders.childs#)}

<script type="text/javascript">
$(document).ready(
  function() {
    $('.orders-list.{getClassIdentifier()}').each(
      function() {
        new OrdersListController(this, {getAJAXRequestParamsAsJSObject():r});
      }
    );
  }
);
</script>
</div>
