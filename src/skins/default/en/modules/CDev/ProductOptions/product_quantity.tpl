{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product options-based quantity box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<tbody IF="product.productOptions&product.inventory.found&!product.tracking">

  <tr>
    <td width="30%" class="ProductDetails">{t(#Quantity#)}:</td>
    <td IF="{product.inventory.amount}" class="ProductDetails" nowrap>{t(#X item(s) available#,_ARRAY(#count#product.inventory.amount))}</td>
    <td IF="{!product.inventory.amount}" class="ErrorMessage" nowrap>{t(#- out of stock -#)}</td>
  </tr>

  <widget module="CDev\ProductAdviser"  class="\XLite\Module\CDev\ProductAdviser\View\NotifyLink" />

</tbody>

<tbody IF="product.productOptions&product.tracking&product.outOfStock">

  <tr>
    <td width="30%" class="ProductDetails">{t(#Quantity#)}:</td>
    <td class="ErrorMessage" nowrap>{t(#- out of stock -#)}</td>
  </tr>

</tbody>
