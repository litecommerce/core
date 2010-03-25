{* SVN $Id$ *}

<ul class="menu menu-tree">
  <li FOREACH="getCategories(rootId),idx,_category" class="{assembleItemClassName(idx,_categoryArraySize,_category)}">
    <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^_category.category_id))}" class="{assembleLinkClassName(idx,_categoryArraySize,_category)}">{_category.name}</a>
    <widget template="{getBody()}" rootId="{_category.category_id}" IF="getCategories(_category.category_id)" is_subtree />
  </li>
</ul>
