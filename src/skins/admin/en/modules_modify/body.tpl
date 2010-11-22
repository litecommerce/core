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
var confirmNotes = [];
confirmNotes['uninstall'] = '{t(#Are you sure you want to uninstall this add-on?#)}';
confirmNotes['enable']    = '{t(#Are you sure you want to enable this add-on?#)}';
confirmNotes['disable']   = '{t(#Are you sure you want to disable this add-on?#)}';
<!-- 
function confirmNote(action)
{
  return confirmNotes[action]
    ? confirm(confirmNotes[action])
    : confirm('{t(#Are you sure?#)}');
}
-->
</script>

<widget class="\XLite\View\Button\Submit" label="{t(#Add new add-ons#)}" />
<br /><br />

{* Display add-ons list *}
<widget template="modules_modify/list.tpl" />
