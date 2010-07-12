{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product options-based quantity box
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<tbody IF="product.productOptions&product.inventory.found&!product.tracking">

  <tr>
    <td width="30%" class="ProductDetails">Quantity:</td>
    <td IF="{product.inventory.amount}" class="ProductDetails" nowrap>{product.inventory.amount} item(s) available</td>
    <td IF="{!product.inventory.amount}" class="ErrorMessage" nowrap>- out of stock -</td>
  </tr>

  <widget module="ProductAdviser"  class="\XLite\Module\ProductAdviser\View\NotifyLink">

</tbody>

<tbody IF="product.productOptions&product.tracking&product.outOfStock">

  <tr>
    <td width="30%" class="ProductDetails">Quantity:</td>
    <td class="ErrorMessage" nowrap>- out of stock -</td>
  </tr>

</tbody>
