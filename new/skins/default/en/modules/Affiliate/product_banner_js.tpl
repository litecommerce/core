banner = "" +
'<table border=0>' +
'<tr IF="product.hasThumbnail()">' +
'    <td align=center><a href="{shopURL(#cart.php#)}?target=product&product_id={product.product_id}&partner={partner}"><img src="{shopURL(#cart.php#,wasSecure,#1#)}?target=image&action=product_thumbnail&id={product.product_id}" border=0></a></td>' +
'</tr>' +
'<tr>' +
'    <td align=center><a href="{shopURL(#cart.php#)}?target=product&product_id={product.product_id}&partner={partner}">{addSlashes(product.name):h}</a></td>' +
'</tr>' +
'</table>';
document.write(banner);
