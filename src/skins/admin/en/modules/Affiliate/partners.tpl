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
<p align=justify>In this section you can manage partners' participation in affiliate programs (review partner information, change status and assign plans).</p>

<br>
<table border=0 cellpadding=5 cellspacing=1>

<form name=partners_list_form action="admin.php" method=GET>
<input type=hidden name=target value=partners>
<input type=hidden name=search value=search>

<tr>
    <td colspan=6 nowrap>
        <table border=0>
        <tr>
            <td>Filter:</td>
            <td><input type=text name=filter value="{filter}"></td>
        </tr>
        <tr>
            <td>Status:</td>
            <td>
                <select name=partnerStatus>
                    <option value="" selected="partnerStatus=##">All</option>
                    <option value="{auth.partnerAccessLevel}" selected="partnerStatus=auth.PartnerAccessLevel">Approved</option>
                    <option value="{auth.PendingPartnerAccessLevel}" selected="partnerStatus=auth.PendingPartnerAccessLevel">Pending</option>
                    <option value="{auth.DeclinedPartnerAccessLevel}" selected="partnerStatus=auth.DeclinedPartnerAccessLevel">Declined</option>
                </select>
            </td>
        </tr>    
        <tr>
            <td valign=top>Partner plan:</td>
            <td>    
                <select name="plan_id">
                    <option value="" selected="{plan_id=##}">All</option>
                    <option FOREACH="xlite.factory.\XLite\Module\Affiliate\Model\AffiliatePlan.findAll(),ap" value="{ap.plan_id}" selected="{ap.plan_id=plan_id}">{ap.title:h}</option>
                </select>
                <br><input type=radio name=plan value=plan checked="plan=#plan#"> Granted plan
                <br><input type=radio name=plan value=pending_plan checked="plan=#pending_plan#"> Sign-up plan
            </td>
        </tr>
        <tr>
            <td>Sign-up date from:</td>
            <td><widget class="\XLite\View\Date" field="startDate"></td>
        </tr>    
        <tr>
            <td>Sign-up date through:</td>
            <td><widget class="\XLite\View\Date" field="endDate"></td>
        </tr>    
        <tr>
            <td>&nbsp;</td>
            <td><input type=submit name=search value=Search></td>
        <tr>
        </table>
    </td>
</tr>
<tr><td colspan=6>&nbsp;</td><tr>

<tr IF="search&!partners">
    <td colspan=6>0 partner(s) found</td>
</tr>
<tr IF="search&partners">
    <td colspan=6>{partnersCount} partner(s) found</td>
</tr>


<tr IF="search&partners">
    <td colspan=3>
        <widget class="\XLite\View\Pager" data="{partners}" name="pager" itemsPerPage="{itemsPerPage}">
    </td>
    <td colspan=2 align=right>
        Items on page:&nbsp;
        <select name=itemsPerPage onchange="document.partners_list_form.submit()">
            <option value=5   selected="itemsPerPage=#5#">5</option>
            <option value=10  selected="itemsPerPage=#10#">10</option>
            <option value=20  selected="itemsPerPage=#20#">20</option>
            <option value=50  selected="itemsPerPage=#50#">50</option>
            <option value=100 selected="itemsPerPage=#100#">100</option>
        </select>
    </td>
</tr>
</form>

<form IF="search&partners" action="admin.php" method=POST>
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type=hidden name=action value=update_partners>

<tr class=TableHead>
    <td>&nbsp;</td>
    <td>Login</td>
    <td nowrap>Partner name</td>
    <td nowrap>Sign-up date</td>
    <td nowrap>Partner plan</td>
    <td>Status</td>
</tr>
<tr FOREACH="pager.pageData,key,partner" class="{getRowClass(key,#TableRow#,##)}">
    <td><input type=checkbox name="ids[]" value="{partner.profile_id}"></td>
    <td nowrap><a href="admin.php?target=profile&profile_id={partner.profile_id}&mode=modify"><u>{partner.login:h}</u></a></td>
    <td>{partner.billing_firstname:h} {partner.billing_lastname:h}&nbsp;&nbsp;&nbsp;(<a href="admin.php?target=profile&profile_id={partner.profile_id}&mode=modify"><u>edit profile...</u></a>)</td>
    <td>{time_format(partner.partner_signup)}</td>
    <td>
        &nbsp;
        {foreach:xlite.factory.\XLite\Module\Affiliate\Model\AffiliatePlan.findAll(),ap}
            {if:ap.plan_id=partner.plan}
                {ap.title:h}
            {end:}
        {end:}
    </td>
    <td>
        <font IF="partner.declinedPartner" color=red>Declined</font>
        <font IF="partner.partner" color=green>Active</font>
        <font IF="partner.PendingPartner" color=blue>Pending</font>
    </td>
</tr>
<tr><td colspan=6>&nbsp;</td><tr>
<tr>
    <td colspan=6>
    <table border=0>
    <tr>
        <td>Assign partner plan:</td>
        <td>
            <select name=new_plan>
                <option value="">- do not change -</option>
                <option value="0">- not assigned -</option>
                <option FOREACH="xlite.factory.\XLite\Module\Affiliate\Model\AffiliatePlan.findAll(),ap" value="{ap.plan_id}">{ap.title:h}</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Set partner status to:</td>
        <td>
            <select name=status>
                <option value="">- do not change -</option>
                <option value=approve>Active</option>
                <option value=pend>Pending</option>
                <option value=decline>Declined</option>
            </select>
        </td>
    </tr>
    </table>
    </td>
</tr>    
<tr>
    <td colspan=6>
        <input type=submit name=update value=" Update ">&nbsp;&nbsp;<input type=submit name=delete value=" Delete ">
    </td>
</tr>

</form>
</table>
