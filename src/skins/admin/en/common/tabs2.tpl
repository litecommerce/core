{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * 2-level tabber template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.18
 *}

<div class="tabs2-tabbed-content-wrapper">
  <div class="tabs2-tabs-container">
    <div class="tabs2-page-tabs">

      <ul>
        <li FOREACH="getTabs(),tabPage" class="tab{if:tabPage.selected}-current{end:}">
          <a IF="!tabPage.selected" href="{tabPage.url:h}">{t(tabPage.title)}</a>
          <div IF="tabPage.selected">{t(tabPage.title)}</div>
          <div IF="tabPage.selected" class="footer"></div>
        </li>
      </ul>

    </div>
    <div class="clear"></div>

    <div class="tab-content">
      <widget template="{getTabTemplate()}" IF="isTemplateOnlyTab()" />
      <widget widget="{getTabWidget()}" IF="isWidgetOnlyTab()" />
      <widget widget="{getTabWidget()}" template="{getTabTemplate()}" IF="isFullWidgetTab()" />
      <widget template="{getPageTemplate()}" IF="isCommonTab()" />
    </div>

  </div>
</div>
