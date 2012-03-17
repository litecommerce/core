{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Entries list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="upgrade.step.prepare.entries_list.sections", weight="100")
 *}

<table class="entries-list">
  <tr class="header"><list name="sections.table.header" type="inherited" /></tr>
  <tr class="separator"><td colspan="6"></td></tr>
  {foreach:getUpgradeEntries(),entry}
  <tr class="{getEntryRowCSSClass(entry)}">
    <list name="sections.table.columns" type="inherited" entry="{entry}" />
    {if:!isModule(entry)}
      </tr><tr class="separator"><td colspan="6"></td>
    {end:}
  </tr>
  {end:}
</table>
