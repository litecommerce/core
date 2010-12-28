{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Top categories tree
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul class="menu menu-tree{if:!isSubtree()} catalog-categories catalog-categories-tree{end:}">
  {foreach:getCategories(rootId),idx,_category}
    <li {displayItemClass(idx,_categoryArraySize,_category):h}>
      <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^_category.getCategoryId()))}" {displayLinkClass(idx,_categoryArraySize,_category):h}>{_category.getName()}</a>
      <widget template="{getBody()}" rootId="{_category.getCategoryId()}" IF="_category.GetSubCategoriesCount()" is_subtree />
    </li>
  {end:}
  {foreach:getViewList(#topCategories.childs#,_ARRAY_(#rootId#^getParam(#rootId#),#is_subtree#^getParam(#is_subtree#))),idx,w}
    <li {displayListItemClass(idx,wArraySize,w):h}>{w.display()}</li>
  {end:}
</ul>
