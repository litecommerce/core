{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Items per page
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.20
 *
 * @ListChild (list="pager.admin.model.table.right", weight="100")
 *}

{if:isItemsPerPageVisible()}
  <span>{t(#Items per page#)}:</span>
  <select name="itemsPerPage" class="page-length not-significant">
    <option FOREACH="getItemsPerPageRanges(),range" value="{range}" selected="{isRangeSelected(range)}">{range}</option>
  </select>
{end:}
