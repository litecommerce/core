{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (table variant)
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<table class="items-list table">

  <tr>
    {displayListPart(#header#,_ARRAY_(#order#^order))}
  </tr>

  <tr FOREACH="getPageData(),idx,order" class="{getRowClass(idx,##,#TableRow#)}">
    {displayListPart(#columns#,_ARRAY_(#order#^order))}
  </tr>

  <tr FOREACH="getViewList(#itemsList.order.admin.items#),w">
    {w.display()}
  </tr>

</table>
