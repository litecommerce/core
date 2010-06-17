{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products search price range block (horizontal)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="advsearch.horizontal.childs", weight="70")
 *}
<tr class="price">
  <td class="row-title">Price, $ (range):</td>
  <td colspan="2">
    <input type="text" class="start wheel-ctrl field-float field-positive" name="search[start_price]" value="{search.start_price}" />&ndash;<input type="text" class="end wheel-ctrl field-float field-positive" name="search[end_price]" value="{search.end_price}" />
  </td>
</tr>
