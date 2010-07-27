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
The form below allows you to create a partner profile. Do not forget that this information is essential to use our services correctly. Mandatory fields are marked with an asterisk (<font class="Star">*</font>). 

<p IF="userExists" class="ErrorMessage">&gt;&gt;&nbsp;The user is already registered! Please select another e-mail.&nbsp;&lt;&lt;</p>
<p IF="invalidPassword" class="ErrorMessage">&gt;&gt;&nbsp;Invalid login or password.&nbsp;&lt;&lt;</p>
<p IF="!valid" class=ErrorMessage>&gt;&gt;&nbsp;There are errors in the form. Your profile has not been updated!&nbsp;&lt;&lt;</p>

<form action="cart.php" method=POST name="modify_form">
<input type="hidden" foreach="allparams,param,v" name="{param}" value="{v}"/>
<input type="hidden" name="action" value="{mode}">

<table border=0 cellspacing=0 cellpadding=2>
<tr valign="middle">
    <td width=150><img src="images/spacer.gif" width=1 height=1 border=0></td>
    <td width=10><img src="images/spacer.gif" width=1 height=1 border=0></td>
    <td width=150><img src="images/spacer.gif" width=1 height=1 border=0></td>
    <td><img src="images/spacer.gif" width=1 height=1 border=0></td>
</tr>
<tr valign="middle">
    <td colspan="4"><b>E-mail &amp; Password</b><br><hr size="1" noshade></td>
</tr>
<tr valign="middle">
    <td align="right">E-mail</td>
    <td width="10"><font color="red">*</font></td>
    <td width="150">
        <input type="text" name="login" value="{login:r}" size="32" maxlength="128">
    </td>
    <td>
        &nbsp;
        <widget class="\XLite\Validator\EmailValidator" field="login">
    </td>
</tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>
Please leave the password fields empty<br> if you don't want to change the password.
</td></tr>
<tr valign="middle">
    <td align="right">Password</td>
    <td>&nbsp;</td>
    <td>
        <input type="password" name="password" value="{password:r}" size="32" maxlength="128">
    </td>
    <td>
        &nbsp;
    </td>
</tr>
<tr valign="middle">
    <td align="right">Confirm password</td>
    <td>&nbsp;</td>
    <td>
        <input type="password" name="confirm_password" value="{confirm_password:r}" size="32" maxlength="128">
    </td>
    <td>
        <widget class="\XLite\Validator\PasswordValidator" field="confirm_password" passwordField="password">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Password hint</td>
    <td>&nbsp;</td>
    <td>
        <input type="text" name="password_hint" value="{password_hint:r}" size="32" maxlength="128">
    </td>
    <td>
    </td>
</tr>
<tr valign="middle">
    <td align="right">Password hint answer</td>
    <td>&nbsp;</td>
    <td>
        <input type="text" name="password_hint_answer" value="{password_hint_answer:r}" size="32" maxlength="128">
    </td>
    <td>
    </td>
</tr>
<tr>
<tr>
    <td colspan="4">&nbsp;</td>
</tr>

<!-- ********************************* BILLING ADDRESS ********************************* -->

<tr valign="middle">
    <td colspan="4"><b>Billing information</b><br><hr size="1" noshade></td>
</tr>
<tr valign="middle">
    <td align="right">Title</td>
    <td>&nbsp;</td>
    <td>
        <select name="billing_title">
            <option value="Mr." selected="{billing_title=#Mr.#}">Mr.</option>
            <option value="Mrs." selected="{billing_title=#Mrs.#}">Mrs.</option>
            <option value="Ms." selected="{billing_title=#Ms.#}">Ms.</option>
        </select>
    </td>
    <td>&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">First Name</td>
    <td>{if:config.Miscellaneous.partner_profile.billing_firstname}<font color="red">*</font>{else:}&nbsp;{end:}</td>
    <td>
        <input type="text" name="billing_firstname" value="{billing_firstname:r}" size="32" maxlength="128">
    </td>
    <td>
        &nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="billing_firstname" IF="{config.Miscellaneous.partner_profile.billing_firstname}">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Last Name</td>
    <td>{if:config.Miscellaneous.partner_profile.billing_lastname}<font color="red">*</font>{else:}&nbsp;{end:}</td>
    <td>
        <input type="text" name="billing_lastname" value="{billing_lastname:r}" size="32" maxlength="128">
    </td>
    <td align="left">
        &nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="billing_lastname" IF="{config.Miscellaneous.partner_profile.billing_lastname}">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Company</td>
    <td>{if:config.Miscellaneous.partner_profile.billing_company}<font  color="red">*</font>{else:}&nbsp;{end:}</td>
    <td>
        <input type="text" name="billing_company" value="{billing_company:r}" size="32" maxlength="255">
    </td>
    <td>
        &nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="billing_company" IF="{config.Miscellaneous.partner_profile.billing_company}">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Phone</td>
    <td>{if:config.Miscellaneous.partner_profile.billing_phone}<font color="red">*</font>{else:}&nbsp;{end:}</td>
    <td>
        <input type="text" name="billing_phone" value="{billing_phone:r}" size="32" maxlength="32">
    </td>
    <td>
        &nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="billing_phone" IF="{config.Miscellaneous.partner_profile.billing_phone}">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Fax</td>
    <td>{if:config.Miscellaneous.partner_profile.billing_fax}<font color="red">*</font>{else:}&nbsp;{end:}</td>
    <td>
        <input type="text" name="billing_fax" value="{billing_fax:r}" size="32" maxlength="32">
    </td>
    <td>
        &nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="billing_fax" IF="{config.Miscellaneous.partner_profile.billing_fax}">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Address</td>
    <td>{if:config.Miscellaneous.partner_profile.billing_address}<font color="red">*</font>{else:}&nbsp;{end:}</td>
    <td>
        <input type="text" name="billing_address" value="{billing_address:r}" size="32" maxlength="64">
    </td>
    <td>
        &nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="billing_address" IF="{config.Miscellaneous.partner_profile.billing_address}">
    </td>
</tr>
<tr valign="middle">
    <td align="right">City</td>
    <td>{if:config.Miscellaneous.partner_profile.billing_city}<font color="red">*</font>{else:}&nbsp;{end:}</td>
    <td>
        <input type="text" name="billing_city" value="{billing_city:r}" size="32" maxlength="64">
    </td>
    <td>
        &nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="billing_city" IF="{config.Miscellaneous.partner_profile.billing_city}">
    </td>
</tr>
<tr valign="middle">
    <td align="right">State</td>
    <td>{if:config.Miscellaneous.partner_profile.billing_state}<font color="red">*</font>{else:}&nbsp;{end:}</td>
    <td>
		<widget class="\XLite\View\StateSelect" field="billing_state" value="{billing_state}">
    </td>
    <td>
        &nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="billing_state" IF="{config.Miscellaneous.partner_profile.billing_state}">
        <widget class="\XLite\Validator\StateValidator" field="billing_state" countryField="billing_country" IF="{config.Miscellaneous.partner_profile.billing_state}">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Country</td>
    <td>{if:config.Miscellaneous.partner_profile.billing_country}<font color="red">*</font>{else:}&nbsp;{end:}</td>
    <td>
		<widget class="\XLite\View\CountrySelect" field="billing_country">
    </td>
    <td>
        &nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="billing_country" IF="{config.Miscellaneous.partner_profile.billing_country}">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Zip code</td>
    <td>{if:config.Miscellaneous.partner_profile.billing_zipcode}<font color="red">*</font>{else:}&nbsp;{end:}</td>
    <td nowrap>
        <input type="text" name="billing_zipcode" value="{billing_zipcode:r}" size="32" maxlength="32">
    </td>
    <td>
        &nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="billing_zipcode" IF="{config.Miscellaneous.partner_profile.billing_zipcode}">
    </td>
</tr>

<tr>
    <td colspan="4">&nbsp;</td>
</tr>

{*extraFields*}

<tr valign="middle">
    <td colspan="4"><b>Partner information</b><br><hr size="1" noshade></td>
</tr>
<tr valign="middle">
    <td align="right">Partner plan requested at sign-up</td>
    <td></td>
    <td>
        <select class="FixedSelect" disabled>
            <option FOREACH="xlite.factory.\XLite\Module\Affiliate\Model\AffiliatePlan.findAll(),ap" value="{ap.plan_id}" selected="{ap.plan_id=pending_plan}">{ap.title:h}</option>
        </select>
        <input type=hidden name="pending_plan" value="{pending_plan}">
    </td>
    <td>&nbsp;</td>
</tr>
<tr valign="middle" IF="!plan=#0#">
    <td align="right">Assigned partner plan</td>
    <td></td>
    <td>
        <select class="FixedSelect" disabled>
            <option FOREACH="xlite.factory.\XLite\Module\Affiliate\Model\AffiliatePlan.findAll(),ap" value="{ap.plan_id}" selected="{ap.plan_id=plan}">{ap.title:h}</option>
        </select>
    </td>
    <td>&nbsp;</td>
</tr>

<!-- ********************************* ADDITIONAL FIELDS ********************************* -->

<widget class="\XLite\Module\Affiliate\View\PartnerField" template="modules/Affiliate/partner_field.tpl" formField="partner_fields" partnerFields="{partnerFields}" partner="{profile}">

</table>

<br>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle">
    <td width="160">&nbsp;</td>
    <td >
        By clicking "SUBMIT" you agree with our <a href="cart.php?target=help&mode=terms_conditions" style="TEXT-DECORATION: underline" target="_blank">Terms &amp; Conditions.</a><br>
        <br>
        <a href="javascript: document.modify_form.submit()"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"><font class="FormButton"> Submit</font></a><br>
    </td>
</tr>
</table>
</form>
