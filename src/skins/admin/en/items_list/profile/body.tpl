{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Profiles list (table variant)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<widget class="\XLite\View\Form\Profiles\ProfilesList" name="profile_list" />

<table class="profile-list data-table items-list">

  <tr>
    <list name="header" type="inherited" profile="{profile}" />
  </tr>

  <tr FOREACH="getPageData(),idx,profile" class="{getRowClass(idx,##,#highlight#)}">
    <list name="columns" type="inherited" profile="{profile}" />
  </tr>

  <tr FOREACH="getViewList(#itemsList.profile.items#),w">
    {w.display()}
  </tr>

</table>

<list name="footer" type="inherited" />

<widget name="profile_list" end />
