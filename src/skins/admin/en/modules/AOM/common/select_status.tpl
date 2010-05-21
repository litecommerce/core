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
{if:pm=#credit_card#}
<script type="text/javascript">
function status_change(el)
    {
if (el.value == '{config.General.clear_cc_info}')
    document.getElementById('status_warning').style.visibility = 'visible';
else
    document.getElementById('status_warning').style.visibility = 'hidden';
}
</script>
{end:}
<select name="{field}" style="{style}" {if:xlite.GoogleCheckoutEnabled&googleCheckoutOrder}disabled{end:} {if:pm=#credit_card#}onChange="javascript: status_change(this)"{end:}>
<option value="" IF="allOption">All</option>
<option FOREACH="statuses,orderStatus" value="{orderStatus.status}" selected="{value=orderStatus.status}">{orderStatus.name}</option>
</select>
<span IF="pm=#credit_card#" id="status_warning" class="DialogTitle" style="color: red; visibility: hidden; white-space: nowrap;">&nbsp;Credit card info will be removed on this status change.</span>
