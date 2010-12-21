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
 *}
<form IF="getModules()" action="admin.php" method="post" name="modules_form_{key}" class="modules-list">
  <input type="hidden" name="target" value="modules">
  <input type="hidden" name="action" value="update">
  <input type="hidden" name="module_type" value="{key}" />

  <table cellspacing="0" cellpadding="0" class="data-table modules-list">

		<tr FOREACH="getModules(),moduleIdx,module" class="{if:!module.getEnabled()} disabled{end:}">
      <td class="icon" width="90">
        <a name="{module.getName()}"></a>
        <div class="icon-container">

          {if:!module.getEnabled()}
            <div class="addon-disabled">
              <img src="images/spacer.gif" width="48" height="48" alt="" />
            </div>
          {end:}
 
          <div class="module-icon">
            {if:module.hasIcon()}
              <img src="{module.getIconURL()}" width="48" height="48" border="0" />
            {else:}
              <img src="images/addon_default.png" width="48" height="48" border="0" />
            {end:}
          </div>
          
       </div>
      </td>
      <td width="40%">
        <div class="name">{getInstalledProperty(module,#moduleName#)}</div>
        <div class="author">{t(#Author#)}: {getInstalledProperty(module,#authorName#)}</div>
        <div class="version">{t(#Version#)}: {getInstalledProperty(module,#version#)}</div>
        <div class="actions">
          {if:module.getEnabled()}
            <a href="{buildUrl(#modules#,#disable#,_ARRAY_(#moduleId#^module.getModuleId()))}" onclick="javascript: return confirmNote('disable', '{module.getModuleId()}');">{t(#Disable#)}</a>
          {else:}
            {if:!module.canEnable()}
              <span class="disabled">{t(#Enable#)}</span>
            {else:}
              <a href="{buildUrl(#modules#,#enable#,_ARRAY_(#moduleId#^module.getModuleId()))}" onclick="javascript: return confirmNote('enable', '{module.getModuleId()}');">{t(#Enable#)}</a>
            {end:}
          {end:}
          {if:module.showSettingsForm()}
            <a href="{module.getSettingsFormLink()}">{t(#Settings#)}</a>
          {end:}
          {if:!module.getEnabled()}
            <a class="uninstall" href="{buildUrl(#modules#,#uninstall#,_ARRAY_(#moduleId#^module.getModuleId()))}" onclick="javascript: return confirmNote('uninstall', '{module.getModuleId()}');">{t(#Uninstall#)}</a>
          {end:}

        </div>

        {if:!module.canEnable()}
        <div class="dependencies">
          {t(#Add-on cannot be enabled.#)}
          {if:getInstalledProperty(module,#dependencies#)}
            <br />
            {t(#The following add-on(s) must be enabled:#)}
            <ul>
              <li FOREACH="module.getDependenciesModules(),depend">
                <a href="#{depend.getName()}">{getInstalledProperty(depend,#moduleName#)} ({t(#by#)} {getInstalledProperty(m,#author#)})</a>
                [ {if:depend.getEnabled()}<span class="good">{t(#enabled#)}</span>{else:}<span class="none">{t(#disabled#)}</span>{end:} ]
              </li>
            </ul>
          {end:}
        </div>
        {end:}

<script type="text/javascript">
depends[{module.getModuleId()}] = [];
{foreach:module.getDependedModules(),k,m}
{if:m.getEnabled()}
depends[{module.getModuleId()}][{k}] = '{getInstalledProperty(m,#moduleName#)} ({t(#by#)} {getInstalledProperty(m,#author#)})';
{end:}
{end:}
</script>

        {if:module.isUpdateAvailable()}
          <div class="upgrade-note">
            {t(#A new version is available#)}
            <br /><br />
            <widget class="\XLite\View\Button\Submit" label="{t(#Upgrade#)}" /> {t(#to v.#)}{module.getLastVersion()}
          </div>
        {end:}

      </td>
      <td width="60%">
        <div class="description">
          {getInstalledProperty(module,#description#)}
        </div>
        <div class="module-url">
          <a href="{module.getPageURL()}" target="_blank">{t(#Visit add-on\'s page#)}</a>
        </div>
      </td>
		</tr>    

  </table>

</form>
