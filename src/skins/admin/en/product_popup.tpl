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
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={charset}">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
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
