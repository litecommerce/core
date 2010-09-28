{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart item : subtotal
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.item", weight="60")
 *}
<td class="item-equal">=</td>
<td class="item-subtotal">
  {formatPrice(item.getTotal(),cart.getCurrency())}
  {displayViewListContent(#cart.item.actions#,_ARRAY_(#item#^item))}
</td>
