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
<table border=0>

<tbody IF="affiliatePlans">
<form FOREACH="affiliatePlans,idx,ap" action="admin.php" method=POST name=affiliate_plans>
<input type=hidden name=target value=affiliate_plans>
<input type=hidden name=action value=update>
<input type=hidden name=plan_id value="{ap.plan_id}">

<tr><td colspan=3>&nbsp;</td></tr>
<tr><td colspan=3 class=AdminHead>Affiliate plan &quot;{ap.title:h}&quot;</td></tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr>
    <td>Plan title</td>
    <td width=10 class=Star>&nbsp;</td>
    <td><input type=text name=title value="{ap.title:h}"></td>
</tr>
<tr>
    <td>Minimum commission payment</td>
    <td width=10>&nbsp;</td>
    <td><input type=text name=payment_limit value="{ap.payment_limit}"></td>
</tr>
<tr>
    <td>Plan status</td>
    <td width=10>&nbsp;</td>
    <td>
        <select name=enabled>
            <option value=1 selected="{ap.enabled=#1#}">Enabled</option>
            <option value=0 selected="{ap.enabled=#0#}">Disabled</option>
        </select>
    </td>    
</tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr>
    <td colspan=3>
        <input type=submit value=" Update ">
        <input type=button value="Commissions.." onClick="document.location='admin.php?target=plan_commissions&plan_id={ap.plan_id}'">
        <input type=button value=" Delete " onClick="document.location='admin.php?target=affiliate_plans&plan_id={ap.plan_id}&mode=delete'">
    </td>
</tr>
<tr><td colspan=3>&nbsp;</td></tr>
</form>

<tr><td colspan=3>&nbsp;</td></tr>

</tbody>

<form action="admin.php#add_plan" method=POST>
<input type=hidden name=target value=affiliate_plans>
<input type=hidden name=action value=add>

<tr><td colspan=3>&nbsp;</td></tr>

<tr><td colspan=3 class=AdminTitle><a name=add_plan></a>Add Affiliate plan</td></tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr>
    <td>Plan title</td>
    <td width=10 class=Star>*</td>
    <td>
        <input type=text name=title value="{affiliatePlan.title:h}">
        <widget class="\XLite\Validator\RequiredValidator" field="title">
    </td>
</tr>
<tr>
    <td>Minimum commission payment</td>
    <td width=10>&nbsp;</td>
    <td><input type=text name=payment_limit value="{affiliatePlan.payment_limit}"></td>
</tr>
<tr>
    <td>Plan status</td>
    <td width=10>&nbsp;</td>
    <td>
        <select name=enabled>
            <option value=1 selected="{affiliatePlan.enabled=#1#}">Enabled</option>
            <option value=0 selected="{affiliatePlan.enabled=#0#}">Disabled</option>
        </select>
    </td>    
</tr>
<tr>
    <td colspan=2>&nbsp;</td>
    <td>
        <input type=checkbox name=returnUrl value="admin.php?target=plan_commissions&plan_id=">
        Redirect to the edit commission rates page after creating plan.
    </td>
</tr>
<tr>
    <td colspan=3>
    <input type=submit value="  Add  "></td>
</tr>

</form>

</table>
