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
