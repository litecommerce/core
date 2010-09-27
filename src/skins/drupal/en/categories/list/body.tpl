{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Top categories list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul class="menu menu-list{if:!isSubtree()} catalog-categories catalog-categories-path{end:}">
  <li FOREACH="getCategories(),idx,_category" class="{assembleItemClassName(idx,_categoryArraySize,_category)}">
    <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^_category.category_id))}" class="{assembleLinkClassName(idx,_categoryArraySize,_category)}">{_category.name}</a>
  </li>
  <li FOREACH="getViewList(#topCategories.childs#,_ARRAY_(#rootId#^getParam(#rootId#),#is_subtree#^getParam(#is_subtree#))),idx,w" class="{assembleListItemClassName(idx,wArraySize,w)}">
    {w.display()}
  </li>
</ul>
