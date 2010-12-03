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
<widget template="common/dialog.tpl" name="searchOrdersForm" head="Search orders" body="order/search_form.tpl" />

<widget template="common/dialog.tpl" head="Recent orders" body="order/recent_orders.tpl" recent_orders="{recentOrders}" IF="{recentOrders}" mode="" />

{* Open <form ...> tag *}
<widget class="\XLite\View\Form\Order\Modify\Batch" name="orders_form" />

  {* List of orders *}
  <widget class="\XLite\View\ItemsList\Order\Admin\Search" />

{* Close </form> tag *}
<widget name="orders_form" end />

<widget module="CDev\AccountingPackage" template="common/dialog.tpl" head="Export found orders to MYOB Plus" body="modules/CDev/AccountingPackage/export_myob_select.tpl" mode="export_myob" />

<widget module="CDev\AccountingPackage" template="common/dialog.tpl" head="Export found orders to Peachtree" body="modules/CDev/AccountingPackage/export_pt_select.tpl" mode="export_pt" />
