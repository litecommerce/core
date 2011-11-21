{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Pager
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div IF="#1#" class="addons-install-pager-found-title">
  {getItemsTotal()} {t(#add-ons found#)}
  <span IF="getTag()">{t(#for"#)} "<span class="tag">{getTag()}</span>" {t(#tag#)}</span>
</div>

<ul class="pager grid-list addons-install-pager-list" IF="isPagesListVisible()">
  <li FOREACH="getPages(),page" class="{page.classes}">
    <a IF="page.href" href="{page.href}" class="{page.page}" title="{page.title}">{page.text:h}</a>
    <span IF="!page.href" class="{page.page}" title="{page.title}">{page.text}</span>
  </li>
</ul>

<div class="clear"></div>
