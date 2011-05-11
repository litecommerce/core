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
 * @ListChild (list="upgrade.step.ready_to_install.entries_list.sections", weight="100")
 *}

<table class="downloaded-components">
  <tr class="header">{displayInheritedViewListContent(#sections.table.header#)}</tr>
  <tr class="module-entry" FOREACH="getUpgradeEntries(),entry">
    {displayInheritedViewListContent(#sections.table.columns#,_ARRAY_(#entry#^entry))}
  </tr>
</table>
