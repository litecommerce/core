{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (table variant)
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
    <td IF="product.sku">{product.sku}</td>
    <td IF="!product.sku">&nbsp;</td> {* Prevent cell collapsing in IE when empty *}
    <td class="product-name-column"><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" class="product-name">{product.name:h}</a></td>
    <td IF="isShowPrice()" class="product-price-column"><widget class="XLite_View_Price" product="{product}" displayOnlyPrice="true" /></td>
    <td IF="isShowAdd2Cart(product)" class="product-button-column"><widget class="XLite_View_BuyNow" product="{product}" style="aux-button add-to-cart" /></td>
  </tr>

  <tr IF="isShowMoreLink()">
    <td colspan="{getTableColumnsCount()}"><a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a></td>
  </tr>

</table>
