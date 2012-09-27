{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Browser server dialog : items
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="browseServer", zone="admin", weight="100")
 *}
<div class="browse-selector">
  <ul class="file-system-entries">
    <li class="file-system-entry up-level">
      {displayCommentedData(getCatalogInfo())}
      <a class="type-catalog up-level"><img src="images/spacer.gif" alt="" />[...]</a>
    </li>
    <li FOREACH="getFSEntries(),idx,entry" class="fs-entry">
      <list name="browseServer.item" entry="{entry}" />
    </li>
    <li IF="isEmptyCatalog()" class="empty-catalog">{t(#Directory is empty#)}</li>
  </ul>
</div>


