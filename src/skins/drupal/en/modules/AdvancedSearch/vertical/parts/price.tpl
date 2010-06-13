{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Search by price range
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (class="XLite_Module_AdvancedSearch_View_AdvancedSearch", weight="20")
 *}
<tr class="search-price">
  <td colspan="3">
    <label for="search-price-start">Price, $ (range):</label>
  </td>
</tr>
<tr class="search-price value-range form-field">
  <td class="start">
    <input type="text" class="start wheel-ctrl field-float field-positive" id="search-price-start" name="search[start_price]" value="{search.start_price}" />
  </td>
  <td class="dash">&ndash;</td>
  <td class="end">
    <input type="text" class="end wheel-ctrl field-float field-positive" id="search-price-end" name="search[end_price]" value="{search.end_price}" />
  </td>
</tr>
