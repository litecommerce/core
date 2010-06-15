{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Vertical minicart items block_
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="#minicart.vertical.childs", weight="10")
 *}
<div class="cart-items" IF="cart.empty">
  <p class="cart-empty">Cart is empty</p>
</div>

<div class="cart-items" IF="!cart.empty">
  <p><span class="toggle-button"><a href="{buildURL(#cart#)}" onClick="javascript:xlite_minicart_toggle('lc-minicart-{displayMode}'); return false;">{cart.getItemsCount()} item(s)</a> </span></p>
  <div class="items-list">
    <ul>
      <li FOREACH="getItemsList(),item">
        <span class="item-name"><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^item.product_id))}">{item.name}</a></span>
        <widget module="ProductOptions" class="XLite_Module_ProductOptions_View_MinicartSelectedOptions" item="{item}" />
        <span class="item-price">{price_format(item,#price#):h}</span><span class="delimiter">x</span><span class="item-qty">{item.amount}</span>
      </li>
    </ul>
    <p IF="isTruncated()" class="other-items"><a href="{buildURL(#cart#)}">Other items</a></p>
  </div>
</div>


