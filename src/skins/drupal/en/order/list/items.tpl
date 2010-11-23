{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order items short list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table cellspacing="0" class="order-list-items">
  <tr FOREACH="getItems(),i,item">

    <td class="image">
      <widget class="\XLite\View\Image" image="{item.getImage()}" alt="{item.getName()}" maxWidth="40" maxHeight="40" centerImage="0" />
    </td>

    <td>
      <ul class="name-qty">
        <li class="name"><a href="{item.getURL()}">{item.name}</a></li>
        <li class="qty">{t(#Qty#)}: <span class="quantity">{item.amount}</span></li>
      </ul>
    </td>

  </tr>

</table>

<script type="text/javascript">
$(document).ready(
  function() {
    // Assign orders items short list constroller
    $('.orders-list ul.list li.order-{order.order_id}').each(
      function() {
        new OrderItemsShortListController(this);
      }
    );
  }
);
</script>
