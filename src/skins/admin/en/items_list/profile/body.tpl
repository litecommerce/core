{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Profiles list (table variant)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<table class="profile-list data-table items-list">

  <tr>
    {displayInheritedViewListContent(#header#,_ARRAY_(#profile#^profile))}
  </tr>

  <tr FOREACH="getPageData(),idx,profile" class="{getRowClass(idx,##,#highlight#)}">
    {displayInheritedViewListContent(#columns#,_ARRAY_(#profile#^profile))}
  </tr>

  <tr FOREACH="getViewList(#itemsList.profile.items#),w">
    {w.display()}
  </tr>

</table>

{displayInheritedViewListContent(#footer#)}
