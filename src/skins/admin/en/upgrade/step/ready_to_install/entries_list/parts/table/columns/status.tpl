{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * If entry disabled or enabled
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="upgrade.step.ready_to_install.entries_list.sections.table.columns", weight="200")
 * @ListChild (list="upgrade.step.completed.entries_list.sections.table.columns", weight="200")
 *}

<td IF="entry.isValid()" class="status enabled"></td>
<td IF="!entry.isValid()" class="status disabled">{t(#Failure#)}</td>
