{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Price widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
{if:isDisplayOnlyPrice()}

  <span class="product-price">{price_format(getProduct(),#listPrice#):h}</span>

{else:}
<span IF="{!isSalePriceEnabled()}">
  <font class="ProductPriceTitle">Price:</font>
  <font class="ProductPrice">{price_format(getProduct(),#listPrice#):h}</font><font class="ProductPriceTitle"> {product.priceMessage:h}</font>
</span>

<span IF="{isSalePriceEnabled()}">
  <font class="ProductPriceTitle">Our price:</font>
  <font class="ProductPrice">{price_format(getProduct(),#listPrice#):h}</font><font class="ProductPriceTitle"> {product.priceMessage:h}</font>
  <br />
  <font class="MarketPrice">Market price: <em>{price_format(getProduct(),#sale_price#):h}</em></font><span IF="{isSaveEnabled()}"> , <font class="Save">save {getSaveValue()}</font></span>
</span>
{end:}
