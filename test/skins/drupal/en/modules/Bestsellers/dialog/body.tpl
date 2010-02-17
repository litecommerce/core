{* SVN $Id$ *}
<table cellpadding="2" cellspacing="2">
  <tr FOREACH="bestsellers,bestseller">
    <td IF="config.Bestsellers.bestsellers_thumbnails" valign="top" align="center" width="30">
      <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^bestseller.product_id,#category_id#^bestseller.category.category_id,#sns_mode#^#bestseller#))}" IF="bestseller.hasThumbnail()"><img src="{product.thumbnailURL}" width="25" alt="" /></a>
    </td>
    <td valign="top">
      <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^bestseller.product_id,#category_id#^bestseller.category.category_id,#sns_mode#^#bestseller#))}"><font class="ItemsList">{bestseller.name:h}</font></a><br />
        Price: {price_format(bestseller,#price#):r}
    </td>
  </tr>
</table>
