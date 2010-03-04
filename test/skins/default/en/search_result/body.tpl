{* SVN $Id$ *}
<div IF="!products">
  No products found on your query. Please try to {if:xlite.AdvancedSearchEnabled}<a href ="{buildURL(#advanced_search#)}" class="FormButton"><u>re-formulate</u></a>{else:}re-formulate{end:} the query.
</div>

<div IF="products">
  {if:xlite.AdvancedSearchEnabled&count}{count} {if:count=#1#}product{else:} products {end:} found. <a class="FormButton" href="{buildURL(#advanced_search#)}"><u>Refine your search</u></a>{end:}

  <widget class="XLite_View_Pager" data="{products}" name="pager">

  <div FOREACH="pager.pageData,product">
    <br />

    <table cellpadding="5" cellspacing="0">
      <tr>
        <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
          <a IF="product.hasThumbnail()" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#substring#^substring))}"><img src="{product.thumbnailURL}" width="70" alt="" /></a>
          <br />
          <a IF="product.hasThumbnail()" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#substring#^substring))}">See&nbsp;details&nbsp;<img src="images/details.gif" width="13" height="13" align="middle" alt="" /></a>
          <br />
          <br />
        </td>
        <td valign="top">
          <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#substring#^substring))}"><font class="ProductTitle">{product.name:h}</font></a>
          <br />
          <br />
          {truncate(product,#brief_description#,#300#):h}
          <br />
          <hr />
          <widget class="XLite_View_Price" product="{product}" template="common/price_plain.tpl">
          <br />
          <br />

          <table cellpadding="0" cellspacing="0">
            <tr>
              <td><widget template="buy_now.tpl" product="{product}"></td>
              <td width="40">&nbsp;</td>
              <td><widget module="WishList" template="modules/WishList/add.tpl" href="{buildURL(#wishlist#,#add#,_ARRAY_(#product_id#^product.product_id))}"></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>

  <widget name="pager">

</div>
