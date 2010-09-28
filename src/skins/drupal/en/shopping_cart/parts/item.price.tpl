{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart item : price
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.item", weight="40")
 *}
<td class="item-price">{formatPrice(item.getPrice(),cart.getCurrency())}</td>
<td class="item-multi">&times;</td>
