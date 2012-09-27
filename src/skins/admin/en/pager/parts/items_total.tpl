{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list items total
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="pager.itemsTotal", weight="20")
 *}

<div IF="isItemsPerPageVisible()" class="pager-items-total">
  {t(#Items#)}:
  <span class="begin-record-number">{getBeginRecordNumber()}</span>
  &ndash;
  <span class="end-record-number">{getEndRecordNumber()}</span> {t(#of#)} <span class="records-count">{getItemsTotal()}</span><span IF="isItemsPerPageSelectorVisible()">, <input type="text" value="{getItemsPerPage()}" class="page-length" /> {t(#per page#)} </span>
</div>

<div class="clear"></div>
