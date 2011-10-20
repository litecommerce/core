{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : order review step : items : list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="checkout.review.selected.items", weight="20")
 * @ListChild (list="checkout.review.inactive.items", weight="20")
 *}
<div class="list" style="display: none;">

  <ul>
    <li FOREACH="cart.getItems(),item">
      <a href="{item.getURL()}">{item.getName()}<img src="images/spacer.gif" alt="" class="fade" /></a>
      <div>
        &times;
        <span class="qty">{item.getAmount()}</span>
      </div>
    </li>
  </ul>

</div>
