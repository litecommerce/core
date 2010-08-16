{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Horizontal minicart total block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="minicart.horizontal.childs", weight="20")
 *}
<div class="cart-totals" IF="!cart.isEmpty()">
  <p><span class="delimiter">/</span><span class="cart-total">{price_format(cart,#total#):h}</span></p>
</div>

