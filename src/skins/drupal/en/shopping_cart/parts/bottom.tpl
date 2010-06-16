{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart butons block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.childs", weight="20")
 *}
<table cellspacing="0" class="cart-bottom">
  <tr>
    <td>

      {displayViewListContent(#cart.bottom.left#)}

    </td>
    <td class="cart-summary">

      {displayViewListContent(#cart.bottom.right#)}

    </td>
  </tr>
</table>
