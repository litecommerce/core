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
<span IF="!banners">There are no available banners</span>

<table IF="banners" border=0>
<tr><td>Placing a banner on your site is very easy. All you need to do is choose a banner, copy the piece of code next to it and insert it into your site page.</td></tr>
<tr><td>&nbsp;</td></tr>
<tbody FOREACH="banners,bidx,banner">
<tr><td>&nbsp;</td></tr>
<tr><td class=TextTitle>&quot;{banner.name:h}&quot; banner</td></tr>
<tr>
    <td><widget name="bannerWidget" class="XLite_Module_Affiliate_View_Banner" type="js" banner="{banner}"></td>
</tr>
<tr>
    <td>
    <textarea cols=80 rows=4><widget name="bannerWidget"></textarea>
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
</tbody>
</table>
