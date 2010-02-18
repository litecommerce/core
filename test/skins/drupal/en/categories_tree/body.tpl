{* SVN $Id$ *}
<style type="text/css">
<!--
  .menu li.collapsed ul
  {
    display: none;
  }
-->
</style>
<ul class="menu">
  <li FOREACH="getCategories(),category" class="{assembleItemClassName(categoryArrayPointer,categoryArraySize,category)}">
    <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^category.category_id))}" class="{assembleLinkClassName(categoryArrayPointer,categoryArraySize,category)}">{category.name}</a>
    {if:category.subcategories}
      <widget template="{dir}/subtree.tpl" categories="{category.subcategories}">
    {end:}
  </li>
</ul>
