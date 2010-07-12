{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products table template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table class="list-body list-body-table" cellspacing="0">

  <tr FOREACH="getPageData(),product" class="item">
    <td>{product.sku}</td>
    <td class="product-name-column"><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" class="product-name">{product.name:h}</a></td>
    <td IF="isShowPrice()" class="product-price-column"><widget class="\XLite\View\Price" product="{product}" displayOnlyPrice="true" /></td>
    <td IF="isShowAdd2Cart(product)" class="product-button-column"><widget class="\XLite\View\BuyNow" product="{product}" style="aux-button add-to-cart" /></td>
  </tr>

  <tr IF="isShowMoreLink()">
    <td colspan="4"><a class="link" href="{getMoreLinkURL()}" onClick="this.blur()">{getMoreLinkText()}</a></td>
  </tr>

</table>
