{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.module.install.columns", weight="70")
 *}
<td width="60%" valign="top">
  <div class="module-info">
    <ul>
      <li>
        <widget class="\XLite\View\VoteBar" rate={module.getRating()} />
      </li>
      <li class="downloads-counter">{module.getDownloads()} downloads</li>
    </ul>
  </div>
  <div class="description">
    {module.getDescription()}
  </div>
  <div class="module-info">
    <ul>
      <li><span class="by">{t(#by#)} <a href="{module.getAuthorPageURL()}" target="_blank">{module.getAuthorName()}</a></li>
      <li><a href="{module.getPageURL()}" target="_blank">{t(#Visit add-on\'s page#)}</a></li>
    </ul>
  </div>
</td>
