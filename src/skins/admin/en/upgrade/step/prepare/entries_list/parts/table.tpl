{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Entries list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="upgrade.step.prepare.entries_list.sections", weight="100")
 *}

<table class="entries-list">
  <tr class="header">{displayInheritedViewListContent(#sections.table.header#)}</tr>
  <tr class="separator"><td colspan="6"></td></tr>
  {foreach:getUpgradeEntries(),entry}
  <tr class="{getEntryRowCSSClass(entry)}">
    {displayInheritedViewListContent(#sections.table.columns#,_ARRAY_(#entry#^entry))}
    {if:!isModule(entry)}
      </tr><tr class="separator"><td colspan="6"></td>
    {end:}
  </tr>
  {end:}
</table>
