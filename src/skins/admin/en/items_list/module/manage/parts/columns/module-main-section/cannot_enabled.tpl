{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules main section list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.module.manage.columns.module-main-section", weight="30")
 *}
<div IF="!canEnable(module)">

  <div IF="getInstalledProperty(module,#dependencies#)" class="note dependencies">
    {t(#The following add-on(s) must be enabled:#)}<br /><br />
    <ul>
      <li FOREACH="module.getDependenciesModules(),depend">
        <a href="#{depend.getName()}">{getInstalledProperty(depend,#moduleName#)} ({t(#by#)} {getInstalledProperty(depend,#author#)})</a>
        [
          <span IF="depend.getEnabled()" class="good">{t(#enabled#)}</span>
          <span IF="!depend.getEnabled()" class="none">{t(#disabled#)}</span>
        ]
      </li>
    </ul>
  </div>

  <div IF="isCoreUpgradeNeeded(module)" class="note version error">
    {t(#The module version is incompatible with current core version.#)}<br />
    <widget class="\XLite\View\Button\Regular" label="Upgrade core" /> {t(#to v.#)} {getMaxCoreVersion(module)}
  </div>

  <div IF="isModuleUpgradeNeeded(module)" class="note version error">
    {t(#The installed module version is incompatible with your core version. Please upgrade the module#)}<br />
    <widget class="\XLite\View\Button\Regular" label="Upgrade" /> {t(#to v.#)} {getMaxModuleVersion(module)}
  </div>

</div>
