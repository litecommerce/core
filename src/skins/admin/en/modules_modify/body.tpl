{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script type="text/javascript">
var confirmUninstallNote = '{t(#Confirm?#)}';
<!-- 
function confirmUninstall()
{
  return confirm(confirmUninstallNote);
}
-->
</script>

Use this section to manage add-on components of your online store.
<p class="adminParagraph">
  <strong class="Star">Warning:</strong> It is strongly recommended that you close the shop for maintenance on the <a href="admin.php?target=settings">General settings</a> page before performing any operations on this page!
</p>

{* Display payment modules *}
<widget template="modules_modify/list.tpl" caption="Payment modules" key="1" IF="getModules(#1#)" />

{* Display shipping modules *}
<widget template="modules_modify/list.tpl" caption="Shipping modules" key="2" IF="getModules(#2#)" />

{* Display connector modules *}
<widget template="modules_modify/list.tpl" caption="Connectors" key="4" IF="getModules(#4#)" />

{* Display regular modules *}
<widget template="modules_modify/list.tpl" caption="Add-ons" key="5" IF="getModules(#5#)" />

{* Display 3rd party modules *}
<widget template="modules_modify/list.tpl" caption="3rd party modules" key="6" IF="getModules(#6#)" />
