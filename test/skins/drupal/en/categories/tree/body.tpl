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
<ul class="menu menu-tree">
  <li FOREACH="getCategories(rootId),idx,_category" class="{assembleItemClassName(idx,_categoryArraySize,_category)}">
    <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^_category.category_id))}" class="{assembleLinkClassName(idx,_categoryArraySize,_category)}">{_category.name}</a>
    <widget template="{getBody()}" rootId="{_category.category_id}" IF="getCategories(_category.category_id)" is_subtree />
  </li>
</ul>
