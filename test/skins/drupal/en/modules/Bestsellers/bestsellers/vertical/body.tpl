{* SVN $Id$ *}
<table border="0" cellpadding="0" cellspacing="0">
  <tr FOREACH="getBestsellers(),id,bestseller">
    <td>
      <strong>{inc(id)}.</strong>&nbsp;<a href="{buildURL(#product#,##,_ARRAY_(#product_id#^bestseller.product_id,#category_id#^bestseller.category_id,#sns_mode#^#bestseller#))}" class="SidebarItems">{bestseller.name}</a>
    </td>
  </tr>
</table>

