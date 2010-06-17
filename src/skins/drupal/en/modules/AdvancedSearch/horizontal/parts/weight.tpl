{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products search weight range block (horizontal)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="advsearch.horizontal.childs", weight="80")
 *}
<tr class="weight">
  <td class="row-title">Weight, {config.General.weight_symbol} (range):</td>
  <td colspan="2">
    <input type="text" class="start wheel-ctrl field-integer field-positive" name="search[start_weight]" value="{search.start_weight}" />&ndash;<input type="text" class="end wheel-ctrl field-integer field-positive" name="search[end_weight]" value="{search.end_weight}" />
  </td>
</tr>
