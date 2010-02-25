{* SVN $Id$ *}
<ul class="menu menu-list">
  <li FOREACH="getCategories(),idx,_category" class="{assembleItemClassName(idx,_categoryArraySize,_category)}">
    <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^_category.category_id))}" class="{assembleLinkClassName(idx,_categoryArraySize,_category)}">{_category.name}</a>
  </li>
</ul>
