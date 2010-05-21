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
<p align=justify>From this page you can search users with the specified parameters, export selected users data and send newsletters to the focused group.</p>

<p><b>NOTE:</b> You need to install Newsletters add-on in order to send newsletters to the focused group.</p>

<br>

<a name=report_form></a>

<form name=ecommerce_report_form action="admin.php" method=POST>
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}"/>
<input type=hidden name=action value=get_data>

<table border="0" cellpadding="1" cellspacing="3">

<widget template="modules/EcommerceReports/period_form.tpl">

<tr>
	<td valign=top>Match:</td>
	<td>
		<select name=match_condition>
			<option value="any" selected="match_condition=#any#">any</option>
			<option value="all" selected="match_condition=#all#">all</option>
		</select>
		&nbsp;of selected products and categories
	</td>
</tr>
<tr>
    <td valign=top>Purchased products:<br>(up to three products)</td>
    <td>
        <table border=0 cellspacing=1 cellpadding=3>
        <tr valign=top>
            <td>
                <widget class="XLite_View_ProductSelect" formName="ecommerce_report_form" formField="product1" removeButton>
            </td>
            <td>
                <select name=product1_mod>
                    <option value="">Select one..</option>
                    <option value=less selected="product1_mod=#less#">Less than</option>
                    <option value=more selected="product1_mod=#more#">More than</option>
                    <option value=equal selected="product1_mod=#equal#">Equal</option>
                </select>
            </td>
            <td>
                <input type=text size=5 maxlength=5 name=product1_qty value="{product1_qty}">
            </td>
            <td>
                (optional)
            </td>
        </tr>            

        <tr valign=top>
            <td>
                <widget class="XLite_View_ProductSelect" formName="ecommerce_report_form" formField="product2" removeButton>
            </td>
            <td>
                <select name=product2_mod>
                    <option value="">Select one..</option>
                    <option value=less selected="product2_mod=#less#">Less than</option>
                    <option value=more selected="product2_mod=#more#">More than</option>
                    <option value=equal selected="product2_mod=#equal#">Equal</option>
                </select>
            </td>
            <td>
                <input type=text size=5 maxlength=5 name=product2_qty value="{product2_qty}">
            </td>
            <td>
                (optional)
            </td>
        </tr>            

        <tr valign=top>
            <td>
                <widget class="XLite_View_ProductSelect" formName="ecommerce_report_form" formField="product3" removeButton>
            </td>
            <td>
                <select name=product3_mod>
                    <option value="">Select one..</option>
                    <option value=less selected="product3_mod=#less#">Less than</option>
                    <option value=more selected="product3_mod=#more#">More than</option>
                    <option value=equal selected="product3_mod=#equal#">Equal</option>
                </select>
            </td>
            <td>
                <input type=text size=5 maxlength=5 name=product3_qty value="{product3_qty}">
            </td>
            <td>
                (optional)
            </td>
        </tr>            
        </table>
    </td>        
</tr>

<widget template="modules/EcommerceReports/categories_form.tpl" label="Purchased products from categories:">

<tr>
    <td>Search by location:</td>
    <td>
        <table border=0>
        <tr>
        <td><input type=radio name=location_address value=billing checked="location_address=#billing#"></td>
        <td>Billing information</td>
        <td>&nbsp;<input type=radio name=location_address value=shipping checked="location_address=#shipping#"></td>
        <td>Shipping information</td>
        <td>&nbsp;<input type=radio name=location_address value="" checked="location_address=##"></td>
        <td>Both</td>
        </tr>
        </table>
    </td>
</tr>

<tr>
    <td>City:</td>
    <td><input type=text name=city value="{city:r}" size=35></td>
</tr>

<tr>
    <td>State:</td>
    <td><widget class="XLite_View_StateSelect" field="state"></td>
</tr>

<tr>
    <td>Country:</td>
    <td><widget class="XLite_View_CountrySelect" field="country"></td>
</tr>

<tr>
    <td>Total purchases:</td>
    <td>
        <table border=0>
        <tr>
            <td>
                <select name=total_purchases_mod>
                    <option value="">Select one..</option>
                    <option value=less selected="total_purchases_mod=#less#">Less than</option>
                    <option value=more selected="total_purchases_mod=#more#">More than</option>
                    <option value=equal selected="total_purchases_mod=#equal#">Equal</option>
                </select>
            </td>
            <td>
                <input type=text size=12 maxlength=12 name=total_purchases value="{total_purchases}">
            </td>
        </tr>
        </table>
    </td>
</tr>

<tr>
    <td>Number of orders:</td>
    <td>
        <table border=0>
        <tr>
            <td>
                <select name=number_mod>
                    <option value="">Select one..</option>
                    <option value=less selected="number_mod=#less#">Less than</option>
                    <option value=more selected="number_mod=#more#">More than</option>
                    <option value=equal selected="number_mod=#equal#">Equal</option>
                </select>
            </td>
            <td>
                <input type=text size=12 maxlength=12 name=number_qty value="{number_qty}">
            </td>
        </tr>
        </table>
    </td>
</tr>

<widget module="Promotion" template="modules/EcommerceReports/coupon_form.tpl">

<widget moule="GiftCertificates" template="modules/EcommerceReports/gc_form.tpl">

<tr>
    <td>Membership:</td>
    <td><widget class="XLite_View_MembershipSelect" template="common/select_membership.tpl" field="membership" allOption></td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td><input type=submit name=search value=" Search "></td>
</tr>
</table>

</form>

<br>

<a name="sales_result"></a>

<p IF="search">{count(profiles)} users(s) found</p>

<form name=ecommerce_reports_data IF="search&profiles" action="admin.php" method=POST>
<input type=hidden name=target value=focused_audience>
<input type=hidden name=action value=profiles>

<p>

<table border=0 cellpadding=3 cellspacing=1>
<tr class="TableHead">
    <td width=10 align=center><input id="profile_ids" type="checkbox" checked onclick="setChecked('ecommerce_reports_data', 'profile_ids', this.checked);"></td>
    <td nowrap width=100% align=left>User</td>
</tr>
<tr FOREACH="profiles,pid,pro" class="{getRowClass(pid,#TableRow#,##)}">
    <td width=10 align=center><input id="profile_ids" type="checkbox" name="profile_ids[]" value="{pro.profile_id}" checked></td>
    <td nowrap><a href="admin.php?target=order_list&login={pro.login:r}"><u>&quot;{pro.billing_title:h} {pro.billing_firstname:h} {pro.billing_lastname:h}&quot; &lt;{pro.login:h}&gt;</u></a></td>
</tr>
</table>

</p>

<p>
<table border=0 cellpadding=1 cellspacing=1>
<tr valign=top>
    <td width=400>
    <table border=0 cellpadding=3 cellspacing=1>
    <tr>
        <td class=AdminTitle colspan=2>Send newsletter<br><br></td>
    </tr>    
    <tr>
        <td>Subject:</td>
        <td><input type=text size=45 name=subject></td>
    </tr>
    <tr>
        <td valign=top>Body:</td>
        <td><textarea name=body cols=45 rows=15></textarea></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
        <input type=button name=send_newsletter value=" Send " onclick="alert('Please install and enable Newsletters add-on first.');"/>
        </td>
    </tr>
    </table>
    </td>

    <td>
    <table border=0 cellpadding=3 cellspacing=1>
    <tr>
        <td class=AdminTitle colspan=2>Export users<br><br></td>
    </tr>        
    <tr>
        <td>Delimiter:</td>
        <td><widget template="common/delimiter.tpl"></td>
    </tr>
    <tr>
        <td colspan=2>Select export fields:</td>
    </tr>
    <tr>
        <td colspan=2>
        <table border=0 cellpadding=1 cellspacing=1>
        <tr FOREACH="exportFields,_field,_comment">
            <td><input type=checkbox name="export_fields[{_field}]" checked="{isDefaultField(_field)}"></td>
            <td>{_comment:h}</td>
        </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td colspan=2>
        <input type=submit name=export_profiles value=" Export ">
        </td>
    </tr>
    </table>
    </td>
</tr>
</table>
</p>

</form>
