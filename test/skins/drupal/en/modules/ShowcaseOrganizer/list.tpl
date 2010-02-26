{* SVN $Id$ *}

<widget class="XLite_View_Pager" data="{category.products}" name="pager">

<div FOREACH="pager.pageData,product">
  <table cellpadding="5" cellspacing="0">
    <tr>
      <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
        <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" IF="product.hasThumbnail()"><img src="{product.thumbnailURL}" width="70" alt=""></a>
        <br />
        <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" IF="product.hasThumbnail()"><font class="SeeDetails">See&nbsp;details&nbsp;</font><img src="images/details.gif" width="13" height="13" align="middle" alt="" /></a>
        <br />
        <br />
      </td>
      <td valign="top" width="100%">
        <table width="100%">
          <tr>
            <td>
              <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}"><font class="ProductTitle">{product.name:h}</font></a>
              <br />
              <br />
            </td>
          </tr>
          <tr IF="config.ShowcaseOrganizer.so_show_description">
            <td>
              {truncate(product,#brief_description#,#300#):h}
              <br />
              <hr />
            </td>
          </tr>
          <tr>
            <td>
      				<span IF="config.ShowcaseOrganizer.so_show_price">
                <font class="ProductPriceTitle">Price: </font><font class="ProductPrice">{price_format(product,#listPrice#):h}</font><font class="ProductPriceTitle"> {product.priceMessage:h}</font>
				        <widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" product="{product}" visible="{!getPriceNotificationSaved(product.product_id)}">
                <br />
                <br />
				      </span>
              <widget template="buy_now.tpl" product="{product}">
            </td>
          </tr>    
        </table>
      </td>
    </tr>
  </table>
</div>

<widget name="pager">

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm">
