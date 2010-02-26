{* SVN $Id$ *}
<ul class="lc-subcategory-list" IF="category.getSubcategories()">
  <li FOREACH="category.getSubcategories(),subcategory">
    <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}" class="lc-subcategory-name">{subcategory.name}</a>
  </li>
</ul>
