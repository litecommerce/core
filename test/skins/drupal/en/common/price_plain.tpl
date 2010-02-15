{* SVN $Id$ *}
<span IF="{!SalePriceEnabled}">
  <font class="ProductPriceTitle">Price: </font><font class="ProductPrice">{price_format(product,#listPrice#):h}</font><font class="ProductPriceTitle"> {product.priceMessage:h}</font>
</span>

<span IF="{SalePriceEnabled}">
  <font class="ProductPriceTitle">Our price: </font><font class="ProductPrice">{price_format(product,#listPrice#):h}</font><font class="ProductPriceTitle"> {product.priceMessage:h}</font>
  <br />
  <font class="MarketPrice">Market price: <em>{price_format(product,#sale_price#):h}</em></font><span IF="{SaveEnabled}"> , <font class="Save">save {SaveValue}</font></span>
</span>
