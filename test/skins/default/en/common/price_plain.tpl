{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<span IF="{!SalePriceEnabled}">
  <font class="ProductPriceTitle">Price: </font><font class="ProductPrice">{price_format(product,#listPrice#):h}</font><font class="ProductPriceTitle"> {product.priceMessage:h}</font>
</span>

<span IF="{SalePriceEnabled}">
  <font class="ProductPriceTitle">Our price: </font><font class="ProductPrice">{price_format(product,#listPrice#):h}</font><font class="ProductPriceTitle"> {product.priceMessage:h}</font>
  <br />
  <font class="MarketPrice">Market price: <em>{price_format(product,#sale_price#):h}</em></font><span IF="{SaveEnabled}"> , <font class="Save">save {SaveValue}</font></span>
</span>
