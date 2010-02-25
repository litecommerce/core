{* SVN $Id$ *}
<div class="lc-subcategory-icons" IF="category.getSubcategories()">
  <ul>
    <li FOREACH="category.getSubcategories(),subcategory">
      <div class="lc-subcategory">
        <div class="lc-subcategory-image">
          <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}">
            <img src="{subcategory.getImageURL()}" border="0" alt="{subcategory.name}" IF="subcategory.hasImage()" />
            <img src="images/no_image.gif" border="0" alt="{subcategory.name}" IF="!subcategory.hasImage()" />
          </a>
        </div>
        <div class="lc-subcategory-name">
          <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}">{subcategory.name}</a>
        </div>
      </div>
    </li>
  </ul>
</div>
