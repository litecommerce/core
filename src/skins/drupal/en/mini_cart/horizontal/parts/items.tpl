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
<div class="cart-items" IF="cart.isEmpty()">
  <p class="cart.isEmpty()">Cart is empty</p>
</div>

<div class="cart-items" IF="!cart.isEmpty()">
  <p class="toggle-button"><a href="{buildURL(#cart#)}" onclick="javascript: xlite_minicart_toggle('lc-minicart-{displayMode}'); return false;">{cart.countItems()} item(s)</a> </p>
  <div class="items-list">
    <ul>
      <li FOREACH="getItemsList(),item">
        {displayViewListContent(#minicart.horizontal.item#,_ARRAY_(#item#^item))}
      </li>
    </ul>
    <p class="other-items"><a href="{buildURL(#cart#)}">View cart</a></p>
  </div>
</div>


