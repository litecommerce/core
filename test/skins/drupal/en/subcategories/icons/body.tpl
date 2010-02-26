{* SVN $Id$ *}
<ul class="lc-subcategory-icons" IF="category.getSubcategories()">
  <li FOREACH="category.getSubcategories(),subcategory">
    {* FF2 requires an extra div in order to display "inner-blocks" properly *}
    <div>
      <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}" class="lc-subcategory-icon">
        <img src="{subcategory.getImageURL()}" border="0" alt="{subcategory.name}" IF="subcategory.hasImage()" />
        <img src="images/no_image.gif" border="0" alt="{subcategory.name}" IF="!subcategory.hasImage()" />
      </a>
      <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}" class="lc-subcategory-name">{subcategory.name}</a>
    </div>
  </li>
</ul>
