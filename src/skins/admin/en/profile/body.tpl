{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Register form template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<script type="text/javascript" src="skins/admin/en/js/billing_address.shipping.js"></script>

Mandatory fields are marked with an asterisk (<font class="Star">*</font>).

<!--hr size="1" noshade-->

<form action="admin.php" method="post" name="profile_form">

  <input type="hidden" foreach="allparams,paramKey,paramValue" name="{paramKey}" value="{paramValue}" />
  <input type="hidden" name="action" value="{mode}" />

  <table width="100%" border="0" cellspacing="0" cellpadding="2">

    <tr IF="success">
      <td colspan="4"><font class="SuccessMessage">&gt;&gt;&nbsp;Profile has been updated successfully&nbsp;&lt;&lt;</font></td>
    </tr>

    <tr IF="!valid">
      <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;There are errors in the form. Profile has not been {if:modify}updated!{else:}created!{end:}&nbsp;&lt;&lt;</font></td>
    </tr>

    <tr IF="userExists">
      <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;The user {login} is already registered! &nbsp;&lt;&lt;</font></td>
    </tr>

    <tr valign="middle">
      <td width=150>&nbsp;</td>
      <td width=10>&nbsp;</td>
      <td width=150>&nbsp;</td>
    </tr>

  </table>

  <table border="0" cellspacing="0" cellpadding="3">

    <tr valign="middle">
      <td width="15%"><img src="images/spacer.gif" width=1 height=1 border=0></td>
      <td width="10"><img src="images/spacer.gif" width=1 height=1 border=0></td>
      <td width="100%"><img src="images/spacer.gif" width=1 height=1 border=0></td>
      <td><img src="images/spacer.gif" width=1 height=1 border=0></td>
    </tr>

    <tr valign="middle">
      <td colspan="4"><b>E-mail & Password</b><br><hr size="1" align=left noshade width="80%"></td>
    </tr>

    <tr valign="middle">
      <td align="right">E-mail</td>
      <td><font class="Star">*</font></td>
      <td>

        <table border="0" cellspacing="0" cellpadding="0">

          <tr valign="middle">
            <td><input type="text" name="login" value="{login:r}" size="32" maxlength="128" /></td>
            <td nowrap>&nbsp;&nbsp;<a IF="mode=#modify#&access_level=#0#" href="javascript: SearchOrders()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <font class="FormButton">View order history</font></a></td>
          </tr>

        </table>

      </td>
      <td>
        <widget class="\XLite\Validator\EmailValidator" field="login" />
      </td>
    </tr>

    <tr valign="middle">
      <td align="right">Password</td>
      <td>{if:mode=#register#}<font class="Star">*</font>{else:}&nbsp;{end:}</td>
      <td>
        <input type="password" name="password" value="{password:r}" size="32" maxlength="128" />
      </td>
      <td>
	    	&nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="password" IF="{mode=#register#}" />
      </td>
    </tr>

    <tr valign="middle">
      <td align="right">Confirm password</td>
      <td>{if:mode=#register#}<font class="Star">*</font>{else:}&nbsp;{end:}</td>
      <td>
        <input type="password" name="confirm_password" value="{confirm_password:r}" size="32" maxlength="128" />
      </td>
      <td>
        &nbsp;
        <widget class="\XLite\Validator\RequiredValidator" field="confirm_password" IF="{mode=#register#}" />
        <widget class="\XLite\Validator\PasswordValidator" field="confirm_password" passwordField="password" IF="{mode=#register#}" />
      </td>
    </tr>

    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>

    <tr valign="middle">
      <td colspan="4"><b>Access Information</b><br><hr size="1" align=left noshade width="80%"></td>
    </tr>

    <tr valign="middle">
      <td align="right">Access level</td>
      <td><font class="Star">*</font></td>
      <td>
        <select id="access_level" name="access_level" onchange="onAccessLevelChange()">
          <option FOREACH="auth.userTypes,userType" id="{userType}" value="{auth.getAccessLevel(userType)}" selected="{auth.getAccessLevel(userType)=access_level}">{userType}</option>
        </select>
      </td>
      <td></td>
    </tr>

    <tr>
      <td align="right">Account status</td>
      <td><font class="Star">*</font></td>
      <td>
        <select name="status">
          <option value="E" selected="{isSelected(status,#E#)}">Enabled</option>
          <option value="D" selected="{isSelected(status,#D#)}">Disabled</option>
        </select>
      </td>
      <td></td>
    </tr>

    <tr>
    	<td align="right">Requested membership</td>
      <td><font class="Star">*</font></td>
      <td>
        <widget class="\XLite\View\MembershipSelect" field="pending_membership" value="{pending_membership}" />
        &nbsp;&nbsp;
        {if:xlite.WholesaleTradingEnabled}
        <widget module="WholesaleTrading" template="modules/WholesaleTrading/profile_grant_membership.tpl" />
        {else:}
        <a IF="mode=#modify#" href="javascript: grantMembership()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <font class="FormButton">Grant membership</font></a>
        {end:}
      </td>
      <td></td>
    </tr>

    <tr>
    	<td align="right" valign=top>Granted membership</td>
      <td valign=top><font class="Star">*</font></td>
      <td>
        <widget class="\XLite\View\MembershipSelect" field="membership" value="{membership}" />
        <widget module="WholesaleTrading" template="modules/WholesaleTrading/membership_history/caption.tpl" membership_history="{membership_history}" />
      </td>
      <td></td>
    </tr>

    <widget module="WholesaleTrading" template="modules/WholesaleTrading/memberships/profile_expiration.tpl" />

    <tr valign="middle">
      <td align="right">Referred by</td>
      <td>&nbsp;</td>
      <td><a href="{referer}"><u>{referer}</u></a></td>
      <td></td>
    </tr>

    <tr valign="middle">
      <td colspan="4">&nbsp;</td>
    </tr>

<!-- ********************************* BILLING ADDRESS ********************************* -->

    <tr valign="middle">
      <td colspan="4"><b>Billing Address</b><br><hr size="1" align=left noshade width="80%"></td>
    </tr>

    <tr valign="middle">
      <td align="right">Title</td>
      <td>&nbsp;</td>
      <td>
        <select name="billing_address.title">
          <option value="Mr." selected="{billing_address.title=#Mr.#}">Mr.</option>
          <option value="Mrs." selected="{billing_address.title=#Mrs.#}">Mrs.</option>
          <option value="Ms." selected="{billing_address.title=#Ms.#}">Ms.</option>
        </select>
      </td>
      <td></td>
    </tr>

    <tr valign="middle">
      <td align="right">First Name</td>
      <td><font class="Star">*</font></td>
      <td>
        <input type="text" name="billing_address.firstname" value="{billing_address.firstname:r}" size="32" maxlength="128" />
      </td>
      <td>
        <widget class="\XLite\Validator\RequiredValidator" field="billing_address.firstname" />
      </td>
    </tr>

    <tr valign="middle">
      <td align="right">Last Name</td>
      <td><font class="Star">*</font></td>
      <td>
        <input type="text" name="billing_address.lastname" value="{billing_address.lastname:r}" size="32" maxlength="128" />
      </td>
      <td align="left">
        <widget class="\XLite\Validator\RequiredValidator" field="billing_address.lastname" />
      </td>
    </tr>

    <tr valign="middle">
      <td align="right">Company</td>
      <td>&nbsp;</td>
      <td>
        <input type="text" name="billing_address.company" value="{billing_address.company:r}" size="32" maxlength="255" />
      </td>        
      <td></td>
    </tr>

    <tr valign="middle">
      <td align="right">Phone</td>
      <td><font class="Star">*</font></td>
      <td>
        <input type="text" name="billing_address.phone" value="{billing_address.phone:r}" size="32" maxlength="32" />
      </td>    
      <td>
        <widget class="\XLite\Validator\RequiredValidator" field="billing_address.phone" />
      </td>
    </tr>

    <tr valign="middle">
      <td align="right">Fax</td>
      <td>&nbsp;</td>
      <td>
        <input type="text" name="billing_address.fax" value="{billing_address.fax:r}" size="32" maxlength="32" />
      </td>
      <td></td>
    </tr>

    <tr valign="middle">
      <td align="right">Address</td>
      <td><font class="Star">*</font></td>
      <td>
        <input type="text" name="billing_address.address" value="{billing_address.address:r}" size="32" maxlength="64" />
      </td>
      <td>
        <widget class="\XLite\Validator\RequiredValidator" field="billing_address.address" />
      </td>
    </tr>

    <tr valign="middle">
      <td align="right">City</td>
      <td><font class="Star">*</font></td>
      <td>
        <input type="text" name="billing_address.city" value="{billing_address.city:r}" size="32" maxlength="64" />
      </td>
      <td>
        <widget class="\XLite\Validator\RequiredValidator" field="billing_address.city" />
      </td>
    </tr>

    <tr valign="middle">
      <td align="right">State</td>
      <td><font class="Star">*</font></td>
      <td>
    		<widget class="\XLite\View\StateSelect" field="billing_address.state" state="{billing_address.state}" isLinked=1 />
      </td>
      <td>
        <widget class="\XLite\Validator\RequiredValidator" field="billing_address.state" />
        <widget class="\XLite\Validator\StateValidator" field="billing_address.state" countryField="billing_address.country" />
      </td>
    </tr>

    <tr valign="middle">
      <td align="right">Other state (specify)</td>
      <td>&nbsp;</td>
      <td><input type="text" name="billing_address.custom_state" value="{billing_address.custom_state:r}" size="32" maxlength="64" />
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr valign="middle">
      <td align="right">Country</td>
      <td><font class="Star">*</font></td>
      <td>
    		<widget class="\XLite\View\CountrySelect" field="billing_address.country" country="{billing_address.country}" />
      </td>
      <td>
        <widget class="\XLite\Validator\RequiredValidator" field="billing_address.country" />
      </td>
    </tr>

    <tr valign="middle">
      <td align="right">Zip code</td>
      <td><font class="Star">*</font></td>
      <td nowrap>
        <input type="text" name="billing_address.zipcode" value="{billing_address.zipcode:r}" size="32" maxlength="32" />
      </td>
      <td>
        <widget class="\XLite\Validator\RequiredValidator" field="billing_address.zipcode" />
      </td>
    </tr>

    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>

<!-- ********************************* SHIPPING ADDRESS ********************************* -->

    <tr valign="middle">
      <td colspan="4"><b>Shipping Address (leave empty if same as billing address)</b>
        <br />
        <hr size="1" align=left noshade width="80%" />
    		<span id="btn_copy_billing"><a href="javascript: void();" onclick="javascript: copyBillingInfo(document.profile_form);">Copy Billing Info</a></span>
    		<span id="btn_modify_shipping" style="display: none;"><a href="javascript: void();" onclick="javascript: OnModifyShippingAddress(document.profile_form);">Modify Shipping address</a></span>
    	</td>
    </tr>

    <tbody id="shipping_body">

      <tr valign="middle">
        <td align="right">Title</td>
        <td>&nbsp;</td>
        <td>
          <select name="shipping_title">
            <option value="Mr." selected="{shipping_title=#Mr.#}">Mr.</option>
            <option value="Mrs." selected="{shipping_title=#Mrs.#}">Mrs.</option>
            <option value="Ms." selected="{shipping_title=#Ms.#}">Ms.</option>
          </select>
        </td>
        <td></td>
      </tr>

      <tr valign="middle">
        <td align="right">First Name</td>
        <td><font class="Star">*</font></td>
        <td>
          <input type="text" name="shipping_firstname" value="{shipping_firstname:r}" size="32" maxlength="128" />
        </td>
        <td>&nbsp;</td>
      </tr>

      <tr valign="middle">
        <td align="right">Last Name</td>
        <td><font class="Star">*</font></td>
        <td>
          <input type="text" name="shipping_lastname" value="{shipping_lastname:r}" size="32" maxlength="128" />
        </td>
        <td align="left">&nbsp;</td>
      </tr>

      <tr valign="middle">
        <td align="right">Company</td>
        <td>&nbsp;</td>
        <td>
          <input type="text" name="shipping_company" value="{shipping_company:r}" size="32" maxlength="255" />
        </td>        
        <td></td>
      </tr>

      <tr valign="middle">
        <td align="right">Phone</td>
        <td><font class="Star">*</font></td>
        <td>
          <input type="text" name="shipping_phone" value="{shipping_phone:r}" size="32" maxlength="32" />
        </td>    
        <td>&nbsp;</td>
      </tr>

      <tr valign="middle">
        <td align="right">Fax</td>
        <td>&nbsp;</td>
        <td>
          <input type="text" name="shipping_fax" value="{shipping_fax:r}" size="32" maxlength="32" />
        </td>
        <td></td>
      </tr>

      <tr valign="middle">
        <td align="right">Address</td>
        <td><font class="Star">*</font></td>
        <td>
          <input type="text" name="shipping_address" value="{shipping_address:r}" size="32" maxlength="64" />
        </td>
        <td>&nbsp;</td>
      </tr>

      <tr valign="middle">
        <td align="right">City</td>
        <td><font class="Star">*</font></td>
        <td>
          <input type="text" name="shipping_city" value="{shipping_city:r}" size="32" maxlength="64" />
        </td>
        <td>&nbsp;</td>
      </tr>

      <tr valign="middle">
        <td align="right">State</td>
        <td><font class="Star">*</font></td>
        <td>
      		<widget class="\XLite\View\StateSelect" field="shipping_state" state="{shipping_state}" isLinked=1 />
        </td>
        <td>
          <widget class="\XLite\Validator\StateValidator" field="shipping_state" countryField="shipping_country" />
        </td>
      </tr>

      <tr valign="middle">
      	<td align="right">Other state (specify)</td>
      	<td>&nbsp;</td>
      	<td><input type="text" name="shipping_custom_state" value="{shipping_custom_state:r}" size="32" maxlength="64" /></td>
      	<td>&nbsp;</td>
      </tr>

      <tr valign="middle">
        <td align="right">Country</td>
        <td><font class="Star">*</font></td>
        <td>
      		<widget class="\XLite\View\CountrySelect" field="shipping_country" country="{shipping_country}" />
        </td>
        <td>&nbsp;</td>
      </tr>

      <tr valign="middle">
        <td align="right">Zip code</td>
        <td><font class="Star">*</font></td>
        <td nowrap>
          <input type="text" name="shipping_zipcode" value="{shipping_zipcode:r}" size="32" maxlength="32" />
        </td>
        <td>&nbsp;</td>
      </tr>

    </tbody>

    <script language="JavaScript">
    	CheckBillingShipping(document.profile_form);
    </script>

      {*extraFields*}

    <widget module="Affiliate" template="modules/Affiliate/partner_fields.tpl" name="partnerProfileForm" />

    <widget module="Promotion" template="modules/Promotion/bonus_points.tpl" />

    <widget module="WholesaleTrading" template="modules/WholesaleTrading/profile_form.tpl" />

  </table>

  <br />

  <table width="100%" border="0" cellspacing="0" cellpadding="2">

    <tr valign="middle">
      <td width="160">&nbsp;</td>
      <td >
        <input IF="getRequestParamValue(#mode#)=#register#" type="submit" name="modify" value="Add profile" />
        <input IF="getRequestParamValue(#mode#)=#modify#|getRequestParamValue(#mode#)=##" type="button" name="modify" value="Update profile" onClick="javascript: document.profile_form.submit()" />
        <br />
      </td>
    </tr>

  </table>

</form>

<script language="javascript">
<!--
var access_level_index = document.profile_form.access_level.selectedIndex;

function onAccessLevelChange()
{
	if (!confirm("Are you sure you want to change access level?")) {
		document.profile_form.access_level.options[access_level_index].selected = true;
	} else {
		access_level_index = document.profile_form.access_level.selectedIndex;
	}
}

function grantMembership()
{
    document.profile_form.membership.value = document.profile_form.pending_membership.value;
    document.profile_form.submit();
}


function SearchOrders()
{
    document.profile_form.target.value = "users";
    document.profile_form.mode.value = "orders";
	document.profile_form.submit();
}

// -->
</script>
