{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<tr IF="!cart.payedByGC=0">
  <td><strong>Paid with GC:</strong></td>
	<td align="right">{price_format(cart,#payedByGC#):h}</td>
</tr>

<tr IF="!cart.payedByGC=0">
  <td colspan="2">
    <widget class="XLite_View_Button" href="{buildURL(#cart#,#remove_gc#,_ARRAY_(#return_target#^target))}" label="Remove GC">
  </td>
</tr>
