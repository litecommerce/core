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
<select {if:!nonFixed}class="FixedSelect"{end:} name="{getParam(#field#)}">
  <option value="%" IF="{getParam(#allOption#)}" selected="{isSelected(#%#,value)}">All memberships</option>
  <option value="" selected="{isSelected(##,value)}">No membership</option>
  <option value="pending_membership" IF="{getParam(#pendingOption#)}" selected="{isSelected(#pending_membership#,getParam(#value#))}">Pending membership</option>
  <option FOREACH="getMemberships(),membership" value="{membership.membership_id}" selected="{isSelected(membership.membership_id,getParam(#value#))}">{membership.name}</option>
</select>
