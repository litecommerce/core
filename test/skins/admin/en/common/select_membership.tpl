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

<select {if:!nonFixed}class="FixedSelect"{end:} name="{getParam(#field#)}" size="1">
  <option value="%" IF="{getParam(#allOption#)}" selected="{isSelected(#%#,value)}">All memberships</option>
  <option value="" selected="{isSelected(##,value)}">No membership</option>
  <option value="pending_membership" IF="{getParam(#pendingOption#)}" selected="{isSelected(#pending_membership#,getParam(#value#))}">Pending membership</option>
  <option FOREACH="config.Memberships.memberships,membership" selected="{isSelected(membership,getParam(#value#))}" value="{membership:r}">{membership}</option>
</select>
