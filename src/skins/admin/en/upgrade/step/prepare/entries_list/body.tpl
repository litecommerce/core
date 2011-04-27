{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * List of upgrade cell entries
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<table cellspacing="0" cellpadding="0" border="1">
  <tr>{displayInheritedViewListContent(#header#)}</tr>
  <tr FOREACH="getUpgradeEntries(),entry" class="{getEntryRowCSSClass(entry)}">
    {displayInheritedViewListContent(#columns#,_ARRAY_(#entry#^entry))}
  </tr>
</table>
