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
<ul class="modifier-subvalues">
  <li FOREACH="getTaxes(),surcharge">
    <span class="subname">{t(#Incl. X#,_ARRAY_(#name#^surcharge.getName()))}</span>
    <span class="subvalue">({formatPrice(surcharge.getValue(),order.getCurrency()):h})</span>
  </li>
</ul>
