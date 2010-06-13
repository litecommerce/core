{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Search by weight range
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (class="XLite_Module_AdvancedSearch_View_AdvancedSearch", weight="30")
 *}
<tr class="search-weight">
  <td colspan="3">
    <label for="search-weight-start">Weight, {config.General.weight_symbol} (range):</label>
  </td>
</tr>
<tr class="search-weight value-range form-field">
  <td class="start">
    <input type="text" class="start wheel-ctrl field-integer field-positive" id="search-weight-start" name="search[start_weight]" value="{search.start_weight}" />
  </td>
  <td class="dash">&ndash;</td>
  <td class="end">
    <input type="text" class="end wheel-ctrl field-integer field-positive" id="search-weight-end" name="search[end_weight]" value="{search.end_weight}" />
  </td>
</tr>
