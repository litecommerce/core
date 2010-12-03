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
<link href="skins/default/en/style.css"  rel="stylesheet" type="text/css">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
</head>
<body>
<widget module="CDev\AntiFraud" template="common/dialog.tpl" body="modules/CDev/AntiFraud/tracking/message.tpl" IF="{response.result.some_problems}" head="Tracking service notes">
<widget module="CDev\AntiFraud" template="common/dialog.tpl" body="modules/CDev/AntiFraud/tracking/form.tpl" head="Track IP address">
</body>
</html>
