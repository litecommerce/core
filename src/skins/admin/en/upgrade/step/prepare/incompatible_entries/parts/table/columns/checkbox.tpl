{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Selector
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="upgrade.step.prepare.incompatible_entries.sections.table.columns", weight="300")
 *}

<td class="disable">
  {* :FIXME: see the FlexyCompiler *}
  <input IF="isModuleToDisable(entry)" id="toDisable{entry.getMarketplaceID()}" type="checkbox" name="toDisable[{entry.getMarketplaceID()}]" value="1" disabled="disabled" checked="1" />
  <input IF="isModuleToDisable(entry)" id="toDisable{entry.getMarketplaceID()}" type="hidden" name="toDisable[{entry.getMarketplaceID()}]" value="1" />
  <input IF="!isModuleToDisable(entry)" id="toDisable{entry.getMarketplaceID()}" type="checkbox" name="toDisable[{entry.getMarketplaceID()}]" value="1" />
  <label for="toDisable{entry.getMarketplaceID()}">{t(#Disable#)}</label>
</td>
