{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Tabber template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<h1 IF="head">{t(head)}</h1>

<div class="tabbed-content-wrapper" IF="getTabberPages()">
  <div class="tabs-container">
    <div class="page-tabs">

      <ul>
        <li FOREACH="getTabberPages(),tabPage" class="tab{if:tabPage.selected}-current{end:}">
          <a href="{tabPage.url}">{t(tabPage.title)}</a>
        </li>
      </ul>

      <div class="list-after-tabs">
        <list name="page.tabs.after" />
      </div>

    </div>
    <div class="clear"></div>

    <div class="tab-content">
      <widget template="{getParam(#body#)}" />
    </div>

  </div>
</div>
