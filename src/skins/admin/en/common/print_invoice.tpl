{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<html>
<head>
<title>{config.Company.company_name}: {t(#Invoice#)}</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="skins/admin/en/style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin=0 topmargin=0 rightmargin=0 bottommargin=0 marginwidth="0" marginheight=0 onLoad="window.print()">
<widget class="\XLite\View\Invoice" order="{getOrder()}" />
</body>
</html>
