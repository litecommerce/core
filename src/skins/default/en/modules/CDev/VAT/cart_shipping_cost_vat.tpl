{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping cost VAT
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.8
 *}
<div class="cart-including-modifiers" style="display: none;">
  <table class="including-modifiers" cellspacing="0">
    <tr FOREACH="getTaxes(),surcharge">
      <td class="name">{t(#Including X#,_ARRAY_(#name#^surcharge.getName()))}:</td>
      <td class="value">{formatPrice(surcharge.getValue(),cart.getCurrency())}</td>
    </tr>
  </table>
</div>
