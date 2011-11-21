{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Entry name and author
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="upgrade.step.ready_to_install.entries_list.sections.table.columns", weight="100")
 * @ListChild (list="upgrade.step.completed.entries_list.sections.table.columns", weight="100")
 *}

<td class="module-info">
  <span class="name">{entry.getName()}</span>
  <span class="author">({t(#by#)} {entry.getAuthor()})</span>
</td>
