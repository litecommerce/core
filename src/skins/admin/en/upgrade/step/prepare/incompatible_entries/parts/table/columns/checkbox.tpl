{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Selector
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="upgrade.step.prepare.incompatible_entries.sections.table.columns", weight="300")
 *}

<td>
  {* :FIXME: see the FlexyCompiler *}
  <input IF="isModuleToDisable(entry)" type="checkbox" name="toDisable[{entry.getModuleID()}]" value="1" disabled="disabled" checked="1" />
  <input IF="!isModuleToDisable(entry)" type="checkbox" name="toDisable[{entry.getModuleID()}]" value="1" />
  <label for="toDisable">{t(#Disable#)}</label>
</td>
