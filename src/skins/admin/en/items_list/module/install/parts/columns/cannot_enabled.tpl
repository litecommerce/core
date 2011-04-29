{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules main section list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.module.install.columns.module-main-section", weight="400")
 *}

<div IF="!canEnable(module)">

  <div IF="module.getDependencies()" class="note dependencies">
    {t(#The following add-on(s) must be enabled:#)}<br />
    <ul class="dependencies-list">
      <li class="dependency-element" FOREACH="module.getDependencyModules(),depend">
        <a href="#{depend.getName()}">{depend.getModuleName()}</a>
        <span IF="depend.getEnabled()" class="module-condition enabled"><span>[</span>{t(#enabled#)}<span>]</span></span>
        <span IF="!depend.getEnabled()" class="module-condition disabled"><span>[</span>{t(#disabled#)}<span>]</span></span>
      </li>
    </ul>
  </div>

  <div IF="isCoreUpgradeNeeded(module)" class="note version error">
    {t(#The module version is incompatible with your core version and cannot be installed#)}<br />
    <span IF="isCoreUpgradeAvailable(module.getMajorVersion())">
      {t(#Please#)} <a href="{buildURL(#upgrade#,##,_ARRAY_(#version#^module.getMajorVersion()))}">upgrade core</a>
    </span>
  </div>

  <div IF="isModuleUpgradeNeeded(module)" class="note version error">
    {t(#The module is available for older core versions only#)}
  </div>

</div>
