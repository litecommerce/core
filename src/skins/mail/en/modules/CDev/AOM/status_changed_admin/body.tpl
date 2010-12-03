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
<body>
Order # {order.order_id:r} has been {order.orderStatus.name}<br>
<p>
Note: {order.orderStatus.notes:h}
<p>
Admin notes: {order.admin_notes:h}
<p>
<widget template="common/invoice.tpl">
<p>
{signature:h}
</body>
</html>
