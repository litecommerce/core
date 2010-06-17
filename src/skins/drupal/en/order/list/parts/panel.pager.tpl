{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders list items block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="orders.panel", weight="10")
 *}
<widget class="XLite_View_Pager_OrdersList" data="{getOrders()}" name="pager" pageId="{getPageId()}" />
