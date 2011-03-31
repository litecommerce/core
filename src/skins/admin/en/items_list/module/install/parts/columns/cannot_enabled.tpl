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
 *
 * @ListChild (list="itemsList.module.install.columns.module-main-section", weight="30")
 *}

<div IF="!canEnable(module)">

  <div IF="module.getDependencies()" class="note dependencies">
    {t(#The following add-on(s) must be enabled:#)}<br /><br />
    <ul>
      <li FOREACH="module.getDependentModules(),depend">
        <a href="#{depend.getName()}">{depend.getModuleName()} ({t(#by#)} {depend.getAuthorName()})</a>
        [
          <span IF="depend.getEnabled()" class="good">{t(#enabled#)}</span>
          <span IF="!depend.getEnabled()" class="none">{t(#disabled#)}</span>
        ]
      </li>
    </ul>
  </div>

  <div IF="isCoreUpgradeNeeded(module)" class="note version error">
    {t(#The module version is incompatible with your core version and cannot be installed#)}<br />
    {t(#Please#)} <widget class="\XLite\View\Button\Regular" label="Upgrade core" />
  </div>

  <div IF="isModuleUpgradeNeeded(module)" class="note version error">
    {t(#The module is available for older core versions only#)}
  </div>

</div>
