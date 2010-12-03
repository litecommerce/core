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
banner = "" +
'<table border=0>' +
'<tr IF="product.hasThumbnail()">' +
'    <td align=center><a href="{getShopUrl(#cart.php#)}?target=product&product_id={product.product_id}&partner={partner}"><img src="{getShopUrl(#cart.php#,wasSecure,#1#)}?target=image&action=product_thumbnail&id={product.product_id}" border=0></a></td>' +
'</tr>' +
'<tr>' +
'    <td align=center><a href="{getShopUrl(#cart.php#)}?target=product&product_id={product.product_id}&partner={partner}">{addSlashes(product.name):h}</a></td>' +
'</tr>' +
'</table>';
document.write(banner);
