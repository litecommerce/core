{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : order review step : selected state : items
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="checkout.review.selected", weight="10")
 *}
<div class="box">

  <div class="items-row">
    <a href="#">{t(#X items#,_ARRAY_(#count#^cart.countQuantity()))}</a> {t(#in bag#)}
    <span class="price">{formatPrice(cart.getSubtotal(),cart.getCurrency())}</span>
  </div>

  <div class="list" style="display: none;">

    <ul>
      <li FOREACH="cart.getItems(),item">
        <a href="{item.getURL()}">{item.getName()}<img src="images/spacer.gif" alt="" class="fade" /></a>
        <div>
          <span class="price">{formatPrice(item.getPrice(),cart.getCurrency())}</span>
          &times;
          <span class="qty">{item.getAmount()}</span>
        </div>
      </li>
    </ul>

  </div>

  <ul class="modifiers">

    <li FOREACH="cart.getVisibleSavedModifiers(),m" class="{m.getCode()}-modifier">
      {m.getName()}
      <span>
        {if:m.isAvailable()}
          {formatPrice(m.getSurcharge(),cart.getCurrency())}
        {else:}
          {t(#n/a#)}
        {end:}
      </span>
    </li>

  </ul>

  <hr />

  <div class="total">
    {t(#Total#)}:
    <span>{formatPrice(cart.getTotal(),cart.getCurrency())}</span>
  </div>

</div>
