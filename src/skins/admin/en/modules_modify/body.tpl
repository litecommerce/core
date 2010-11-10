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

<widget class="\XLite\View\Button\Submit" label="{t(#Add new add-ons#)}" />
<br /><br />

{* Display add-ons list *}
<widget template="modules_modify/list.tpl" />
