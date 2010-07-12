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
<widget class="\XLite\View\Pager" data="{products}" name="pager" itemsPerPage="{config.General.products_per_page}">

<table border=0 width="100%">
<tbody FOREACH="pager.pageData,pidx,product">
<tr>
    <td class=ProductTitle colspan=2><a href="{getShopUrl(#cart.php#)}?target=product&product_id={product.product_id}&partner={auth.profile.profile_id}" target="_blank"><u>{product.name}</u></a></td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr>
    <td>Banner source:</td>
    <td align=center width="100%" class=TextTitle>Preview:</td>
</tr>
<tr>
    <td valign=top>
    <textarea cols=50 rows=4><script language="javascript" src="{getShopUrl(#cart.php#)}?target=product_banner&product_id={product.product_id}&partner={auth.profile.profile_id}"></script></textarea>
    <span IF="config.Affiliate.enable_advanced_banner">
    <br><br><br><br>
    <input type=button name=update value="Customize the banner" onclick="document.location='cart.php?target=partner_product&product_id={product.product_id}&backUrl={url:u}'">
    </span>
    </td>
    <td align=center>
    <script language="javascript" src="{getShopUrl(#cart.php#,secure,#1#)}?target=product_banner&product_id={product.product_id}&partner={auth.profile.profile_id}"></script>
    </td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
</tbody>
</table>

<widget class="\XLite\View\Pager" data="{products}" name="pager">
