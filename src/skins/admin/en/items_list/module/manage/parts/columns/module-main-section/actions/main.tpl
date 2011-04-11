{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules actions list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.module.manage.columns.module-main-section.actions", weight="10")
 *}

{if:module.getEnabled()}

  <span IF="{!canDisable(module)}" class="disabled">{t(#Disable#)}</span>
  <a IF="{canDisable(module)}" href="{buildURL(#modules#,#disable#,_ARRAY_(#moduleId#^module.getModuleId()))}" onclick="javascript: return confirm(confirmNote('disable', '{module.getModuleId()}'));">{t(#Disable#)}</a>

  <a IF="{module.callModuleMethod(#showSettingsForm#)}" href="{module.getSettingsForm()}">{t(#Settings#)}</a>

{else:}

  <span IF="{!canEnable(module)}" class="disabled">{t(#Enable#)}</span>
  <a IF="{canEnable(module)}" href="{buildURL(#modules#,#enable#,_ARRAY_(#moduleId#^module.getModuleId()))}" onclick="javascript: return confirm(confirmNote('enable', '{module.getModuleId()}'));">{t(#Enable#)}</a>

{end:}
