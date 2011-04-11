{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * 
 * @ListChild (list="itemsList.module.manage.sections", weight="300")
 *}

<div class="modules-filters">

  <ul class="activity">
    <li FOREACH="getFilters(),filterId,description" class="{getFilterClasses(filterId)}">
      <a IF="filterId" href="{buildURL(#modules#,##,_ARRAY_(#filter#^filterId,#tag#^getTag()))}">{t(description)}</a>
      <a IF="!filterId" href="{buildURL(#modules#)}">{t(description)}</a>
      <span>({getModulesCount(filterId)})</span>
    </li>
  </ul>

  <div class="clear"></div>
</div>
