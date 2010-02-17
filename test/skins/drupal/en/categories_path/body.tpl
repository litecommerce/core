{* SVN $Id$ *}
<ul class="menu">
  <li FOREACH="categories,category" class="{assembleItemClassName(categoryArrayPointer,categoryArraySize,category)}">
    <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^category.category_id))}" class="{assembleLinkClassName(categoryArrayPointer,categoryArraySize,category)}">{category.name}</a>
    {if:category.subcategories}
      <widget template="{widget.dir}/body.tpl" categories="{category.subcategories}">
    {end:}
  </li>
</ul>
