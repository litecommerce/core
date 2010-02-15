{* SVN $Id$ *}

<widget class="XLite_View_Pager" data="{category.products}" name="pager">

<table width="100%" cellpadding="3" cellspacing="0">
  <tbody FOREACH="split(pager.pageData,config.ShowcaseOrganizer.so_columns),row">
    <tr>
    	<td FOREACH="row,product" align="center" width="{getPercents(config.ShowcaseOrganizer.so_columns)}%" valign="top">
        <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" IF="product&config.General.show_thumbnails&product.hasThumbnail()"><img src="{product.thumbnailURL}" width="70" alt=""></a>
      </td>
    </tr>    
    <tr>
    	<td FOREACH="row,product" align="center" width="{getPercents(config.ShowcaseOrganizer.so_columns)}%" valign="top">
      	<a IF="product" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}"><font class="ProductTitle">{product.name:h}</font></a>
    	</td>
    </tr>        
    <tr>
    	<td FOREACH="row,product" align="center" width="{getPercents(config.ShowcaseOrganizer.so_columns)}%" valign="top">
      	<span IF="product&config.ShowcaseOrganizer.so_show_price">
          <font class="ProductPriceTitle">Price: </font><font class="ProductPrice">{price_format(product,#listPrice#):h}</font><font class="ProductPriceTitle"> {product.priceMessage:h}</font>
          <br />
          <widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" product="{product}" visible="{!getPriceNotificationSaved(product.product_id)}">
          <br />
        </span>
	    </td>
    </tr>
    <tr>
    	<td FOREACH="row,product" align="center" width="{getPercents(config.ShowcaseOrganizer.so_columns)}%" valign="top">
        <widget IF="product" template="buy_now.tpl" product="{product}"/>
        <br />
        <br />
      </td>
    </tr>    
  </tbody>
</table>

<widget name="pager">

<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/notify_form.tpl">
