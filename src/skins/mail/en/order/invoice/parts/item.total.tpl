{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Total item cell
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="invoice.item", weight="40")
 *}
<td bgcolor="#CCCCCC" align="right" rowspan="2">{formatPrice(item.getTotal(),order.getCurrency())}</td>
