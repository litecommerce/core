{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules actions list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.module.manage.columns.module-main-section.actions", weight="10")
 *}
<span class="disable" IF="{module.getEnabled()}">
  <input type="hidden" name="switch[{module.getModuleId()}][old]" value="1" />
  {* To prevent disabling modules with the disabled checkbox *}
  <input IF="!canDisable(module)" type="hidden" name="switch[{module.getModuleId()}][new]" value="1" />
  <label for="switch{module.getModuleId()}">
  <input
    type="checkbox"
    name="switch[{module.getModuleId()}][new]"
    {if:!canDisable(module)} disabled="disabled"{end:}
    checked="checked"
    id="switch{module.getModuleId()}" />
  {t(#Enabled#)}</label>
</span>

<span class="enable" IF="{!module.getEnabled()}">
  <input type="hidden" name="switch[{module.getModuleId()}][old]" value="0" />
  <label for="switch{module.getModuleId()}">
  <input
    type="checkbox"
    name="switch[{module.getModuleId()}][new]"
    {if:!canEnable(module)} disabled="disabled"{end:}
    id="switch{module.getModuleId()}" />
  {t(#Enabled#)}</label>
</span>
