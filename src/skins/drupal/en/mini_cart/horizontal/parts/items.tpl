{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Horizontal minicart items block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="minicart.horizontal.childs", weight="10")
 *}
<div class="cart-items" IF="cart.empty">
  <p class="cart-empty">Cart is empty</p>
</div>

<div class="cart-items" IF="!cart.empty">
  <p class="toggle-button"><a href="{buildURL(#cart#)}" onclick="javascript: xlite_minicart_toggle('lc-minicart-{displayMode}'); return false;">{cart.getItemsCount()} item(s)</a> </p>
  <div class="items-list">
    <ul>
      <li FOREACH="getItemsList(),item">
        <span class="item-name"><a href="{item.getUrl()}">{item.name}</a></span>
        <widget module="ProductOptions" class="XLite_Module_ProductOptions_View_MinicartSelectedOptions" item="{item}" />
        <span class="item-price">{price_format(item,#price#):h}</span><span class="delimiter">x</span><span class="item-qty">{item.amount}</span>
      </li>
    </ul>
    <p class="other-items"><a href="{buildURL(#cart#)}">View cart</a></p>
  </div>
</div>


