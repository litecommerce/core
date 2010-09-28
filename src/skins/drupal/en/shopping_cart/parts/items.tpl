{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart items block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.childs", weight="10")
 * @ListChild (list="checkout.cart", weight="10")
 *}
<table class="selected-products" cellspacing="0">

  <tr>
    {displayViewListContent(#cart.items.header#)}
  </tr>

  <tr class="selected-product" FOREACH="cart.getItems(),item">
    {displayViewListContent(#cart.item#,_ARRAY_(#item#^item))}
  </tr>

  <tr class="selected-product additional-item" FOREACH="getViewList(#cart.items#),w">
    {w.display()}
  </tr>

</table>
