{* SVN $Id$ *}
<table IF="category.subcategories" cellpadding="5" cellspacing="0">
  <tr>
    <td valign="top" IF="category.hasImage()">
      <img src="{category.getImageURL()}" alt="" />
    </td>
    <td valign="top">
      <table FOREACH="category.subcategories,subcategory">
        <tr>
          <td>
            <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}"><font class="ItemsList">{subcategory.name}</font></a>
          </td>
        </tr>    
      </table>
    </td>
  </tr>
</table>
