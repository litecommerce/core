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
Use the menus on the left to manage every aspect of your online store.
<br>
From this page, you can build and maintain the store in a very easy way!
<hr>

<table border=0 cellspacing=0 width=90% align="center">
<tr>
    <td align="center">
		<table border="0" cellspacing="0" cellpadding="3" align="center">
        <tr>
            <widget template="menu_item.tpl" href="admin.php?target=order_list" icon="menu/icon_orders.gif" text="<span class='MainMenuItemHeader'>Orders</span><br>Manage orders placed at your store">
            <td width="1%">&nbsp;&nbsp;</td>
            <widget template="menu_item.tpl" href="admin.php?target=orders_stats" icon="menu/icon_statistics.gif" text="<span class='MainMenuItemHeader'>Statistics</span><br>Review statistics on various aspects of your store's operation">
        </tr>
        <tr>
            <td colspan=5>&nbsp;</td>
        </tr>
        <tr>
            <widget template="menu_item.tpl" href="admin.php?target=users" icon="menu/icon_users.gif" text="<span class='MainMenuItemHeader'>Users</span><br>Manage customer and administrator accounts<br><br><a href='admin.php?target=profile&mode=register' style='color:blue;'><u>Add new user</u></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='admin.php?target=import_users' style='color:blue;'><u>Import users</u></a>">
            <td width="1%">&nbsp;&nbsp;</td>
            <widget template="menu_item.tpl" href="admin.php?target=settings" icon="menu/icon_general.gif" text="<span class='MainMenuItemHeader'>General Settings</span><br>Configure your store">
        </tr>
        <tr>
            <td colspan=5>&nbsp;</td>
        </tr>
        <tr>
            <widget template="menu_item.tpl" href="admin.php?target=categories&category_id=0" icon="menu/icon_categories.gif" text="<span class='MainMenuItemHeader'>Categories</span><br>Setup your online catalog structure">
            <td width="1%">&nbsp;&nbsp;</td>
            <widget template="menu_item.tpl" href="admin.php?target=product_list" icon="menu/icon_products.gif" text="<span class='MainMenuItemHeader'>Products</span><br>Manage your product inventory<br><br><a href='admin.php?target=add_product' style='color:blue;'><u>Add product</u></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='admin.php?target=extra_fields' style='color:blue;'><u>Global extra fields</u></a>">
        </tr>
        <tr>
            <td colspan=5>&nbsp;</td>
        </tr>
        <tr>
            <widget template="menu_item.tpl" href="admin.php?target=wysiwyg" icon="menu/icon_design.gif" text="<span class='MainMenuItemHeader'>Design Import/Export</span><br>Make the design of your store appealing and unique.<br><br><a href='admin.php?target=template_editor' style='color:blue;'><u>Template editor</u></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='admin.php?target=css_edit' style='color:blue;'><u>CSS editor</u></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='admin.php?target=image_edit' style='color:blue;'><u>Image editor</u></a>">
            <td width="1%">&nbsp;&nbsp;</td>
            <widget template="menu_item.tpl" href="admin.php?target=modules" icon="menu/icon_modules.gif" text="<span class='MainMenuItemHeader'>Modules</span><br>Expand the functionality of your store by installing and using add-on modules">
        </tr>
        <tr>
            <td colspan=5>&nbsp;</td>
        </tr>
        <tr>
            <widget template="menu_item.tpl" icon="menu/icon_catalog.gif" text="<span class='MainMenuItemHeader'>Store Maintenance</span><br>Perform backups of your store's important data<br><br><a href='admin.php?target=import_catalog' style='color:blue;'><u>Import catalog</u></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='admin.php?target=export_catalog' style='color:blue;'><u>Export catalog</u></a>">
            <td width="1%">&nbsp;&nbsp;</td>
            <widget template="menu_item.tpl" href="quickstart/index.html" icon="menu/icon_quick_start.gif" text="<span class='MainMenuItemHeader'>Quick Start Wizard</span><br>Several configuration steps are required before you can start your sales. Quick Start Wizard guides you through the basic configuration steps.">
        </tr>
		</table>
    </td>
</tr>
</table>
