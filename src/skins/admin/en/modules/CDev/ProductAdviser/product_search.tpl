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
 *                 
 * @ListChild (list="product.search.conditions", weight="60")
 *}

<tr IF="target=#product_list#">
  <td class="FormButton" noWrap height=10 colspan="3">
      Show new arrivals only
      <input type="checkbox" name="new_arrivals_search" checked="{new_arrivals_search}" value="1">
  </td>
</tr>
<tr IF="target=#product#&page=#related_products#">
  <td class="FormButton" noWrap height=10 colspan="3">
      Show new arrivals only
      <input type="checkbox" name="new_arrivals_search" checked="{new_arrivals_search}" value="1">
  </td>
</tr>
