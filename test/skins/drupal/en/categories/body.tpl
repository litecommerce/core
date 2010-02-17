{* SVN $Id$ *}
<ul class="menu">
  <li FOREACH="categories,category" class="{category.className}"><a href="{buildURL(#category#,##,_ARRAY_(#category_id#^category.category_id))}" class="{category.linkClassName}">{category.name}</a></li>
</ul>
