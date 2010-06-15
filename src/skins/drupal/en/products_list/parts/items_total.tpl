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
 * @ListChild (list="productsList.pager", weight="20")
 *}
<div IF="!onlyPages">
  Items:
  <span class="begin-record-number">{pager.getBeginRecordNumber()}</span>
  &ndash;
  <span class="end-record-number">{pager.getEndRecordNumber()}</span> of <span class="records-count">{pager.getItemsTotal()}</span><span IF="pager.isItemsPerPageSelectorVisible()">, <input type="text" value="{pager.getItemsPerPage()}" class="page-length" /> per page</span>
</div>
