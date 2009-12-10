<span IF="{!SalePriceEnabled}">
<FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT>
</span>

<span IF="{SalePriceEnabled}">
<FONT class="ProductPriceTitle">Our price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT>
<br />
<FONT class="MarketPrice">Market price: <s>{price_format(product,#sale_price#):h}</s></FONT><span IF="{SaveEnabled}"> , <font class="Save">save {SaveValue}</font></span>
</span>
