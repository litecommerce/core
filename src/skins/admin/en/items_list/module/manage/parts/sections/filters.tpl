{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.module.manage.sections", weight="300")
 *}

<div class="modules-filters">

  <ul class="activity">
    <li FOREACH="getFilters(),filterId,description" class="{getFilterClasses(filterId)}">
      <a IF="filterId&getModulesCount(filterId)" href="{buildURL(#addons_list_installed#,##,_ARRAY_(#filter#^filterId,#tag#^getTag()))}">{t(description)}</a>
      <span IF="filterId&!getModulesCount(filterId)">{t(description)}</span>
      <a IF="!filterId" href="{buildURL(#addons_list_installed#)}">{t(description)}</a>
      <span>({getModulesCount(filterId)})</span>
    </li>
  </ul>

  <div class="clear"></div>
</div>
