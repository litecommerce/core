{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.6
 *}

<div class="browse-selector">
  <ul class="file-system-entries">
    <li class="file-system-entry up-level">
      {displayCommentedData(getCatalogInfo())}
      <a class="type-catalog up-level"><img src="images/spacer.gif" alt="" />[...]</a>
    </li>
    <li FOREACH="getFSEntries(),idx,entry" class="fs-entry">
      <a class="type-{entry.type} extension-unknown extension-{entry.extension}"><img src="images/spacer.gif" alt="" /><span>{entry.name}</span></a>
    </li>
    <li IF="isEmpty()" class="empty-catalog">{t(#Directory is empty#)}</li>
  </ul>
</div>
<div class="browse-selector-actions">
  <button class="back-button">{t(#Back to file select#)}</button>
  <button class="choose-file-button main-button">{t(#Choose file#)}</button>
</div>
