{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list items total
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="pager.itemsTotal", weight="20")
 *}

<div IF="isItemsPerPageVisible()" class="pager-items-total">
  {t(#Items#)}:
  {t(#BEGIN - END of TOTAL#,_ARRAY_(#begin#^getBeginRecordNumber(),#end#^getEndRecordNumber(),#total#^getItemsTotal())):r}<span IF="isItemsPerPageSelectorVisible()">,
    <input type="text" value="{getItemsPerPage()}" class="page-length" title="{t(#Items per page#)}" />{t(#per page#)}</span>
</div>
