{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * If entry disabled or enabled
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="upgrade.step.prepare.entries_list.sections.table.columns", weight="200")
 *}

{if:entry.isEnabled()}
  <td class="status">&nbsp;</td>
{else:}
  <td IF="entry.isInstalled()" class="status disabled">{t(#Now disabled#)}</td>
  <td IF="!entry.isInstalled()" class="status not-installed">{t(#Will be installed#)}</td>
{end:}
