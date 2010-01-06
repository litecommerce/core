banner = '';
{if:banner.align=#top#}
banner += '' +
'<table border=0>' +
'<tr IF="banner.body">' +
'    <td align=center><a href="{shopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}">{addSlashes(banner.body):h}</a></td>' +
'</tr>' +
'<tr>' +
'    <td><a href="{shopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}"><img src="{shopUrl(#cart.php#,secure,#1#)}?target=image&action=banner_image&id={banner_id}" border=0 alt="{addSlashes(banner.alt)}"></a></td>' +
'</tr>' +
'</table>';
{end:}
{if:banner.align=#bottom#}
banner += '' +
'<table border=0>' +
'<tr>' +
'    <td><a href="{shopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}"><img src="{shopUrl(#cart.php#,secure,#1#)}?target=image&action=banner_image&id={banner_id}" border=0 alt="{addSlashes(banner.alt)}"></a></td>' +
'</tr>' +
'<tr IF="banner.body">' +
'    <td align=center><a href="{shopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}">{addSlashes(banner.body):h}</a></td>' +
'</tr>' +
'</table>';
{end:}
{if:banner.align=#left#}
banner += '' +
'<table border=0>' +
'<tr>' +
'    <td IF="banner.body" valign=middle><a href="{shopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}">{addSlashes(banner.body):h}</a></td>' +
'    <td><a href="{shopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}"><img src="{shopUrl(#cart.php#,secure,#1#)}?target=image&action=banner_image&id={banner_id}" border=0 alt="{addSlashes(banner.alt)}"></a></td>' +
'</tr>' +
'</tr>' +
'</table>';
{end:}
{if:banner.align=#right#}
banner += '' +
'<table border=0>' +
'<tr>' +
'    <td><a href="{shopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}"><img src="{shopUrl(#cart.php#,secure,#1#)}?target=image&action=banner_image&id={banner_id}" border=0 alt="{addSlashes(banner.alt)}"></a></td>' +
'    <td IF="banner.body" valign=middle><a href="{shopUrl(#cart.php#)}?banner_id={banner.banner_id}&partner={partner}" target="{banner.link_target}">{addSlashes(banner.body):h}</a></td>' +
'</tr>' +
'</tr>' +
'</table>';
{end:}
document.write(banner);
