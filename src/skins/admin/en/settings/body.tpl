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
<a href="admin.php?target=settings" class="SidebarItem">General settings</a><br>
<a href="admin.php?target=modules" class="SidebarItem">Modules</a><br>
<a href="admin.php?target=payment_methods" class="SidebarItem">Payment methods</a><br>
<a href="admin.php?target=card_types" class="SidebarItem">Credit card types</a><br>
<hr>
<a href="admin.php?target=shipping_methods">Shipping settings</a><br>
<a href="admin.php?target=taxes">Taxes</a><br>
<a href="admin.php?target=countries">Countries</a><br>
<span IF="xlite.MultiCurrencyEnabled"><a href="admin.php?target=currencies">Currencies</a><br></span>
<a href="admin.php?target=states">States</a><br>
<widget module="AOM" template="modules/AOM/menu.tpl">
<a href="admin.php?target=memberships">Membership levels</a><br>
{*<hr>
<a href="quickstart/index.html">Quick Start Wizard</a><br>*}
