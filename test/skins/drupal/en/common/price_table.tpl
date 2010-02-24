{* SVN $Id$ *}
<tbody IF="{SalePriceEnabled}">
  <tr>
    <td width="30%" class="ProductPriceTitle">Our price:</td>
    <td class="ProductPrice">{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></td>
  </tr>
  <tr>
    <td width="30%" class="MarketPrice">Market price:</td>
    <td class="MarketPrice"><s>{price_format(product,#sale_price#):h}</s><span IF="{SaveEnabled}"> , <font class="Save">save {SaveValue}</font></span></td>
  </tr>
</tbody>
<tbody IF="{!SalePriceEnabled}">
  <tr>
    <td width="30%" class="ProductPriceTitle">Price:</td>
    <td class="ProductPrice">{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></td>
  </tr>
</tbody>
