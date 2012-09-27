{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Top categories list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<ul class="menu menu-list{if:!isSubtree()} catalog-categories catalog-categories-path{end:}">
  {foreach:getCategories(),idx,_category}
    <li {displayItemClass(idx,_categoryArraySize,_category):h}>
      <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^_category.category_id))}" {displayLinkClass(idx,_categoryArraySize,_category):h}>{_category.name}</a>
    </li>
  {end:}
  {foreach:getViewList(#topCategories.children#,_ARRAY_(#rootId#^getParam(#rootId#),#is_subtree#^getParam(#is_subtree#))),idx,w}
    <li {displayListItemClass(idx,wArraySize,w):h}>{w.display()}</li>
  {end:}
</ul>
