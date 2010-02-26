{* Product list inside a category ('icons' mode) *}

<widget class="XLite_View_Pager" data="{category.products}" name="pager">

<table border="0" width="100%" cellpadding="3" cellspacing="0">
<tbody FOREACH="split(pager.pageData,config.LayoutOrganizer.columns),row">
<tr>
	<td FOREACH="row,product" align="center" width="{getPercents(config.LayoutOrganizer.columns)}%" valign="top">
        <a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}" IF="product&config.General.show_thumbnails&product.hasThumbnail()"><img src="{product.thumbnailURL}" border=0 width=70 alt=""></a>
    </td>
</tr>    
<tr>
	<td FOREACH="row,product" align="center" width="{getPercents(config.LayoutOrganizer.columns)}%" valign="top">
    	<a IF="product" href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}"><FONT class="ProductTitle">{product.name:h}</FONT></a>
	</td>
</tr>        
<tr>
	<td FOREACH="row,product" align="center" width="{getPercents(config.LayoutOrganizer.columns)}%" valign="top">
    	<span IF="product&config.LayoutOrganizer.show_price"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT><br>
    	<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" product="{product}" visible="{!getPriceNotificationSaved(product.product_id)}"><br></span>
	</td>
</tr>
<tr>
	<td FOREACH="row,product" align="center" width="{getPercents(config.LayoutOrganizer.columns)}%" valign="top">
        <widget IF="product" template="buy_now.tpl" product="{product}"/>
        <br><br>
    </td>
</tr>    
</tbody>
</table>

<widget name="pager">

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm">
