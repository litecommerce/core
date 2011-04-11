{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * 
 * @ListChild (list="itemsList.module.manage.sections", weight="100")
 *}

{* :TODO: move this code into a separate JS file *}
<script type="text/javascript">
var confirmNotes = [];
confirmNotes['uninstall'] = '{t(#Are you sure you want to uninstall this add-on?#)}';
confirmNotes['enable']    = '{t(#Are you sure you want to enable this add-on?#)}';
confirmNotes['disable']   = '{t(#Are you sure you want to disable this add-on?#)}';

var dependedAlert = '{t(#The following dependent add-ons will be automatically disabled:#)}';
var depends = [];

<!--
function confirmNote(action, id)
{

  var extraTxt = '';
  if (action == 'disable' && id !== undefined && depends[id] && depends[id].length > 0) {
    extraTxt = "\n" + dependedAlert + "\n";
    for (i in depends[id]) {
      extraTxt += depends[id][i] + "\n";
    }
  }

  return confirmNotes[action]
    ? confirm(confirmNotes[action] + extraTxt)
    : confirm('{t(#Are you sure?#)}');
}
-->
</script>
