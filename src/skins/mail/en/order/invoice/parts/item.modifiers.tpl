{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Total item cell
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="invoice.item", weight="40")
 *}
<td FOREACH="item.getThroughExcludeSurcharges(),surcharge" class="modifier {surcharge.getType()}-modifier" rowspan="2">
  {if:surcharge}
    {formatPrice(surcharge.getValue(),order.getCurrency())}
  {else:}
    -
  {end:}
</td>
