{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="product.modify.list", weight="80")
 *}
<tr>
  <td valign="middle">{t(#Free shipping#)}</td>
  <td valign="middle">
   <select name="{getNamePostedData(#free_shipping#)}">
        <option value="1" selected="{isSelected(product,#free_shipping#,#1#)}">Yes</option>
        <option value="0" selected="{isSelected(product,#free_shipping#,#0#)}">No</option>
    </select>
  </td>
</tr>
