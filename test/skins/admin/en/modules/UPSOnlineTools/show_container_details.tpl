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
{if:hasUPSValidContainers()}
{if:style=#button#}
<input class="ProductDetailsTitle" type="button" value="Container details" onClick="window.open('admin.php?target=order&mode=container_details&order_id={order.order_id}')">
{else:}
<br>
<b><a href="admin.php?target=order&mode=container_details&order_id={order.order_id}" target="_blank"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Show container details</a></b>
{end:}
{end:}
