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

  <div class="activity">
    <tbody FOREACH="getFilters(),fltr,desc">
      <a IF="fltr" href="{buildURL(#modules#,##,_ARRAY_(#filter#^fltr))}" class="upgradable{if:fltr=getFilter()} current{end:}">{t(desc)}</a>
      <a IF="!fltr" href="{buildURL(#modules#)}" class="upgradable{if:fltr=getFilter()} current{end:}">{t(desc)}</a>
      <span>({getModulesCount(fltr)})</span>
    </tbody>
  </div>

  <div class="clear"></div>

</div>
