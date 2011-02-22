{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
Use the menus on the left to manage every aspect of your online store.
<br />
From this page, you can build and maintain the store in a very easy way!
<hr />

<table width="80%" cellspacing="0" cellpadding="3">
<tr>
  <td>
		<table cellspacing="0" cellpadding="3">
        <tr>
            <widget template="menu_item.tpl" href="admin.php?target=order_list" icon="menu/icon_orders.gif" text="<span class='main-menu-item-header'>Orders</span><br />Manage orders placed at your store">
            <td style="width:1%;">&nbsp;&nbsp;</td>
            <widget template="menu_item.tpl" href="admin.php?target=settings" icon="menu/icon_general.gif" text="<span class='main-menu-item-header'>General Settings</span><br />Configure your store">
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <widget template="menu_item.tpl" href="admin.php?target=categories" icon="menu/icon_categories.gif" text="<span class='main-menu-item-header'>Categories</span><br />Setup your online catalog structure">
            <td style="width:1%;">&nbsp;&nbsp;</td>
            <widget template="menu_item.tpl" href="admin.php?target=orders_stats" icon="menu/icon_statistics.gif" text="<span class='main-menu-item-header'>Statistics</span><br />Review statistics on various aspects of your store's operation">
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <widget template="menu_item.tpl" href="admin.php?target=product_list" icon="menu/icon_products.gif" text="<span class='main-menu-item-header'>Products</span><br />Manage your product inventory<br /><br /><a href='admin.php?target=product' style='color:blue;'>Add product</a>">
            <td style="width:1%;">&nbsp;&nbsp;</td>
            <widget template="menu_item.tpl" href="admin.php?target=modules" icon="menu/icon_modules.gif" text="<span class='main-menu-item-header'>Add-ons</span><br />Expand the functionality of your store by installing and using add-on modules">
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <widget template="menu_item.tpl" href="admin.php?target=users&mode=search" icon="menu/icon_users.gif" text="<span class='main-menu-item-header'>Users</span><br />Manage customer and administrator accounts<br /><br /><a href='admin.php?target=profile&mode=register' style='color:blue;'>Add new user</a>">
            <td style="width:1%;">&nbsp;&nbsp;</td>
            <td></td>
        </tr>
		</table>
  </td>
</tr>
</table>
<br />
<hr />
<br />
<widget class="\XLite\View\ItemsList\Product\Admin\LowInventory" />
