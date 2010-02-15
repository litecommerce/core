{* Categories menu body *}
<ul class="menu" FOREACH="categories,category">
  <li class="leaf last"><a href="{buildURL(#category#,##,_ARRAY_(#category_id#^category.category_id))}">{category.name}</a></li>
</ul>
