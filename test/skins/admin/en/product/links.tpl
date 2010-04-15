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
Use HTML code provided in this section to create product-specific 'Add to Cart' links for direct placement on your site's static pages. <hr>
<br>

<table border="0" width="100%">
<tr>
    <td colspan="2" class="AdminHead">Link to product thumbnail</td>
</tr>    
<tr><td colspan="2">&nbsp;</td></tr>
{if:product.hasThumbnail()}
<tr>
    <td valign="top">Example:</td>
    <td><img src="{getShopUrl(#cart.php#)}?target=image&action=product_thumbnail&product_id={product.product_id}" border="0" width="70" alt="{product.name:h}">
    </td>
</tr>    
<tr>
    <td valign="top">HTML code:</td>
    <td><textarea cols="80" rows="5"><img src="{getShopUrl(#cart.php#)}?target=image&action=product_thumbnail&product_id={product.product_id}" border="0" width="70" alt="{product.name:h}"></textarea>
    </td>
</tr>
{else:}
<tr><td colspan="2"><i>Product has no thumbnail</i></td></tr>
{end:}
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
    <td colspan="2" class="AdminHead">Simple HTML link to add product to cart</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
    <td valign="top">Example:</td>
    <td><a href="{getShopUrl(#cart.php#)}?target=product&action=buynow&product_id={product.product_id}&category_id={product.category.category_id}">"Add to cart"</a>
    </td>
</tr>
<tr>
    <td valign="top">HTML code:</td>
    <td><textarea cols="80" rows="5"><a href="{getShopUrl(#cart.php#)}?target=product&action=buynow&product_id={product.product_id}&category_id={product.category.category_id}">Add to cart</a></textarea>
    </td>
</tr>    
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
    <td colspan="2" class="AdminHead">HTML button to add product to cart</td>
</tr>    
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
    <td>Example:</td>
    <td><input type="button" value="Add to cart" onclick="document.location='{getShopUrl(#cart.php#)}?target=product&action=buynow&product_id={product.product_id}&category_id={product.category.category_id}'">
    </td>
</tr>    
<tr>
    <td valign="top">HTML code:</td>
    <td><textarea cols="80" rows="5"><input type="button" value="Add to cart" onclick="document.location='{getShopUrl(#cart.php#)}?target=product&action=buynow&product_id={product.product_id}&category_id={product.category.category_id}'"></textarea>
    </td>
</tr>    
</table>
