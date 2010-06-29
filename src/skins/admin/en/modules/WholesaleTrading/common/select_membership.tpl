{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Membership selection template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

{if:!isEmpty(history)}
<select class="FixedSelect" id="{field}" name="{getParam(#field#)}" onchange="javascript: ChangeMembership(this);">
{else:}
<select class="FixedSelect" id="{getParam(#field#)}" name="{getParam(#field#)}">
{end:}

   <option value="%" IF="{getParam(#allOption#)}" selected="{isSelected(#%#,getParam(#value#))}">All memberships</option>
   <option value="0" selected="{isSelected(##,getParam(#value#))}">No membership</option>
   <option value="pending_membership" IF="pendingOption" selected="{isSelected(#pending_membership#,getParam(#value#))}">Pending membership</option>
   <option FOREACH="getMemberships(),membership" selected="{isSelected(membership.membership_id,getParam(#value#))}" value="{membership.membership_id}">{membership.name}</option>
</select>

{if:!isEmpty(history)}
<script type="text/javascript">
var membership_index = document.getElementById('{getParam(#field#)}').selectedIndex;
function ChangeMembership(obj)
{
	if (confirm('Are you sure you want to change mempership?')) {
		membership_index = obj.selectedIndex;

	} else {
		obj.options[membership_index].selected = true;
	}
}
</script>
{end:}
