{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list items total
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="pager.itemsTotal", weight="20")
 *}

<div IF="isItemsPerPageVisible()" class="pager-items-total">
  Items:
  <span class="begin-record-number">{getBeginRecordNumber()}</span>
  &ndash;
  <span class="end-record-number">{getEndRecordNumber()}</span> of <span class="records-count">{getItemsTotal()}</span><span IF="isItemsPerPageSelectorVisible()">, <input type="text" value="{getItemsPerPage()}" class="page-length" /> per page</span>
</div>
<div class="clear"></div>
