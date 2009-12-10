<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={charset}">
    <LINK href="skins/admin/en/style.css"  rel=stylesheet type=text/css>
</head>
<body class="PopUp" LEFTMARGIN=3 TOPMARGIN=3 RIGHTMARGIN=3 BOTTOMMARGIN=3 MARGINWIDTH=0 MARGINHEIGHT=0>

<widget template="product/search.tpl">
{if:mode=#search#}
{if:products}
<widget template="common/popup_product_list.tpl">
{else:}
No products found
{end:}
{end:}
</body>
