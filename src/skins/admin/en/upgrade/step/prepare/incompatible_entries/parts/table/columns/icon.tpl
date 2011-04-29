{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Module status icon
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="upgrade.step.prepare.incompatible_entries.sections.table.columns", weight="200")
 *}

<td IF="isModuleToDisable(entry)">X</td>
<td IF="!isModuleToDisable(entry)">?</td>
