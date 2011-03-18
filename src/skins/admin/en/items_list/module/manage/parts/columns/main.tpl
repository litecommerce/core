{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.module.manage.columns", weight="50")
 *}

{* FIXME: divide into lists and small parts *}

<td style="width: 20px;">
  <div class="name">{getInstalledProperty(module,#moduleName#)}</div>
  <div class="author">{t(#Author#)}: {getInstalledProperty(module,#authorName#)}</div>
  <div class="version">{t(#Version#)}: {getInstalledProperty(module,#version#)}</div>

  <div class="actions">{displayNestedViewListContent(#actions#,_ARRAY_(#module#^module))}</div>

  <div IF="!canEnable(module)">
    {t(#Add-on cannot be enabled.#)}

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
      {t(#The installed module version is incompatible with your core version. Please upgrade the core#)}<br />
      <widget class="\XLite\View\Button\Regular" label="Upgrade core" /> {t(#to v.#)} {getMaxCoreVersion(module)}
    </div>

    <div IF="isModuleUpgradeNeeded(module)" class="note version error">
      {t(#The installed module version is incompatible with your core version. Please upgrade the module#)}<br />
      <widget class="\XLite\View\Button\Regular" label="Upgrade" /> {t(#to v.#)} {getMaxModuleVersion(module)}
    </div>

  </div>

  <div IF="isModuleUpgradeAvailable(module)" class="note version upgrade">
    {t(#A new version is available#)}<br />
    <widget class="\XLite\View\Button\Regular" label="Upgrade" /> {t(#to v.#)} {getMaxModuleVersion(module)}
  </div>

<script type="text/javascript">
depends[{module.getModuleId()}] = [];
{foreach:module.getDependedModules(),k,m}
{if:m.getEnabled()}
depends[{module.getModuleId()}][{k}] = '{getInstalledProperty(m,#moduleName#)} ({t(#by#)} {getInstalledProperty(m,#author#)})';
{end:}
{end:}
</script>

</td>
