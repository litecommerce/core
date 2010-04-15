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
banner = '';
{if:banner.align=#top#}
banner += '' +
'<table border=0>' +
'<tr IF="banner.body">' +
'    <td align=center><a href="{getShopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}">{addSlashes(banner.body):h}</a></td>' +
'</tr>' +
'<tr>' +
'    <td><a href="{getShopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}"><img src="{getShopUrl(#cart.php#,secure,#1#)}?target=image&action=banner_image&id={banner_id}" border=0 alt="{addSlashes(banner.alt)}"></a></td>' +
'</tr>' +
'</table>';
{end:}
{if:banner.align=#bottom#}
banner += '' +
'<table border=0>' +
'<tr>' +
'    <td><a href="{getShopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}"><img src="{getShopUrl(#cart.php#,secure,#1#)}?target=image&action=banner_image&id={banner_id}" border=0 alt="{addSlashes(banner.alt)}"></a></td>' +
'</tr>' +
'<tr IF="banner.body">' +
'    <td align=center><a href="{getShopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}">{addSlashes(banner.body):h}</a></td>' +
'</tr>' +
'</table>';
{end:}
{if:banner.align=#left#}
banner += '' +
'<table border=0>' +
'<tr>' +
'    <td IF="banner.body" valign=middle><a href="{getShopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}">{addSlashes(banner.body):h}</a></td>' +
'    <td><a href="{getShopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}"><img src="{getShopUrl(#cart.php#,secure,#1#)}?target=image&action=banner_image&id={banner_id}" border=0 alt="{addSlashes(banner.alt)}"></a></td>' +
'</tr>' +
'</tr>' +
'</table>';
{end:}
{if:banner.align=#right#}
banner += '' +
'<table border=0>' +
'<tr>' +
'    <td><a href="{getShopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}"><img src="{getShopUrl(#cart.php#,secure,#1#)}?target=image&action=banner_image&id={banner_id}" border=0 alt="{addSlashes(banner.alt)}"></a></td>' +
'    <td IF="banner.body" valign=middle><a href="{getShopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}">{addSlashes(banner.body):h}</a></td>' +
'</tr>' +
'</tr>' +
'</table>';
{end:}
document.write(banner);
