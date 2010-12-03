{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product wholesale prices list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<strong class="subtitle">Buy more for less:</strong>
<table class="wholesale-prices">

  <tr FOREACH="product.getWholesalePricing(),wholesale_price">
    <td class="quantity">{wholesale_price.amount} items</td>
    <td>&mdash;</td>
	  <td class="price">{price_format(wholesale_price.price):r}</td>
  </tr>

</table>
