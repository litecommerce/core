{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.module.manage.columns", weight="50")
 *}
<td width="40%">
  <div class="name">{getInstalledProperty(module,#moduleName#)}</div>
  <div class="author">{t(#Author#)}: {getInstalledProperty(module,#authorName#)}</div>
  <div class="version">{t(#Version#)}: {getInstalledProperty(module,#version#)}</div>
  <div class="actions">
    {if:module.getEnabled()}
      <a href="{buildUrl(#modules#,#disable#,_ARRAY_(#moduleId#^module.getModuleId()))}" onclick="javascript: return confirmNote('disable', '{module.getModuleId()}');">{t(#Disable#)}</a>
      <a IF="module.showSettingsForm()" href="{module.getSettingsFormLink()}">{t(#Settings#)}</a>
    {else:}
      <span IF="!canEnable(module)" class="disabled">{t(#Enable#)}</span>
      <a IF="canEnable(module)" href="{buildUrl(#modules#,#enable#,_ARRAY_(#moduleId#^module.getModuleId()))}" onclick="javascript: return confirmNote('enable', '{module.getModuleId()}');">{t(#Enable#)}</a>
    {end:}
    <a IF="!module.getEnabled()" class="uninstall" href="{buildUrl(#modules#,#uninstall#,_ARRAY_(#moduleId#^module.getModuleId()))}" onclick="javascript: return confirmNote('uninstall', '{module.getModuleId()}');">{t(#Uninstall#)}</a>
  </div>

  <div IF="!canEnable(module)" class="dependencies">
    {t(#Add-on cannot be enabled.#)}
    <div IF="getInstalledProperty(module,#dependencies#)">
      <br />
      {t(#The following add-on(s) must be enabled:#)}
      <ul>
        <li FOREACH="module.getDependenciesModules(),depend">
          <a href="#{depend.getName()}">{getInstalledProperty(depend,#moduleName#)} ({t(#by#)} {getInstalledProperty(m,#author#)})</a>
          [ 
            <span IF="depend.getEnabled()" class="good">{t(#enabled#)}</span>
            <span IF="!depend.getEnabled()" class="none">{t(#disabled#)}</span>
          ]
        </li>
      </ul>
    </div>
  </div>

<script type="text/javascript">
depends[{module.getModuleId()}] = [];
{foreach:module.getDependedModules(),k,m}
{if:m.getEnabled()}
depends[{module.getModuleId()}][{k}] = '{getInstalledProperty(m,#moduleName#)} ({t(#by#)} {getInstalledProperty(m,#author#)})';
{end:}
{end:}
</script>

  <div IF="module.isUpdateAvailable()" class="upgrade-note">
    {t(#A new version is available#)}
    <br />
    <widget class="\XLite\View\Button\Submit" label="{t(#Upgrade#)}" /> {t(#to v.#)}{module.getLastVersion()}
  </div>

</td>
