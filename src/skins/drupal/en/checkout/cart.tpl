{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Cart
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div id="shopping-cart" class="checkout-cart">

  <widget module="ProductOptions" template="modules/ProductOptions/selected_options_js.tpl">

  {displayViewListContent(#checkout.cart#)}

</div>

<div class="clear">&nbsp;</div>
