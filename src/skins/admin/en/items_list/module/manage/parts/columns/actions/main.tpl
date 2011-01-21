{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules actions list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.module.manage.columns.actions", weight="10")
 *}
{if:module.getEnabled()}
  <a href="{buildUrl(#modules#,#disable#,_ARRAY_(#moduleId#^module.getModuleId()))}" onclick="javascript: return confirmNote('disable', '{module.getModuleId()}');">{t(#Disable#)}</a>
  {if:module.showSettingsForm()}
    <a href="{module.getSettingsFormLink()}">{t(#Settings#)}</a>
  {end:}
{else:}
  {if:!canEnable(module)}
    <span class="disabled">{t(#Enable#)}</span>
  {else:}
    <a href="{buildUrl(#modules#,#enable#,_ARRAY_(#moduleId#^module.getModuleId()))}" onclick="javascript: return confirmNote('enable', '{module.getModuleId()}');">{t(#Enable#)}</a>
  {end:}
{end:}
