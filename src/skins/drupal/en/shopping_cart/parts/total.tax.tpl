{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping cart tax totals
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.totals", weight="40")
 *}
<li IF="!cart.getDisplayTaxes()"><em>Tax:</em>
  n/a
</li>
<li FOREACH="cart.getDisplayTaxes(),tax_name,tax"><em>{cart.getTaxLabel(tax_name)}:</em>
  {price_format(tax):h}
</li>
