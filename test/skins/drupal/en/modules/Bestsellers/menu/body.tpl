{* SVN $Id$ *}
<table border="0" cellpadding="0" cellspacing="0">
  <tr FOREACH="bestsellers,id,bestseller">
    <td>
      <strong>{inc(id)}.</strong>&nbsp;<a href="cart.php?target=product&amp;sns_mode=bestseller&amp;product_id={bestseller.product_id}&amp;category_id={bestseller.category_id}" class="SidebarItems">{bestseller.name}</a>
    </td>
  </tr>
</table>

