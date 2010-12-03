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
This page allows you to configure partner registration form <a href="#default_fields"><u>default fields</u></a>, <a href="#add_field"><u>add new field</u></a> or <a href="#additional_fields"><u>modify existing additional fields</u></a>.
<p>
<form action="admin.php" method=POST>
<input type=hidden name=target value=partner_form>
<input type=hidden name=action value=default_fields>

<table border=0>
<tr><td class=AdminHead colspan=2><a name=default_fields>Registration form default fields</a></td></tr>

<tr><td colspan=2>&nbsp;</td></tr>
<tr><td class=TableHead colspan=2 align=center>Email and password fields</td></tr>
<tr><td width=10><input type=checkbox name="default_fields[password_hint]" checked="{config.Miscellaneous.partner_profile.password_hint}"></td><td>Password hint</td></tr>
<tr><td><input type=checkbox name="default_fields[password_hint_answer]" checked="{config.Miscellaneous.partner_profile.password_hint_answer}"></td><td>Password hint answer</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>
<tr><td class=TableHead colspan=2 align=center>Billing information fields</td></tr>
<tr><td><input type=checkbox name="default_fields[billing_title]" checked="{config.Miscellaneous.partner_profile.billing_title}"></td><td>Title</td></tr>
<tr><td><input type=checkbox name="default_fields[billing_firstname]" checked="{config.Miscellaneous.partner_profile.billing_firstname}"></td><td>First Name</td></tr>
<tr><td><input type=checkbox name="default_fields[billing_lastname]" checked="{config.Miscellaneous.partner_profile.billing_lastname}"></td><td>Last Name</td></tr>
<tr><td><input type=checkbox name="default_fields[billing_company]" checked="{config.Miscellaneous.partner_profile.billing_company}"></td><td>Company</td></tr>
<tr><td><input type=checkbox name="default_fields[billing_phone]" checked="{config.Miscellaneous.partner_profile.billing_phone}"></td><td>Phone</td></tr>
<tr><td><input type=checkbox name="default_fields[billing_fax]" checked="{config.Miscellaneous.partner_profile.billing_fax}"></td><td>Fax</td></tr>
<tr><td><input type=checkbox name="default_fields[billing_address]" checked="{config.Miscellaneous.partner_profile.billing_address}"></td><td>Address</td></tr>
<tr><td><input type=checkbox name="default_fields[billing_city]" checked="{config.Miscellaneous.partner_profile.billing_city}"></td><td>City</td></tr>
<tr><td><input type=checkbox name="default_fields[billing_state]" checked="{config.Miscellaneous.partner_profile.billing_state}"></td><td>State</td></tr>
<tr><td><input type=checkbox name="default_fields[billing_country]" checked="{config.Miscellaneous.partner_profile.billing_country}"></td><td>Country</td></tr>
<tr><td><input type=checkbox name="default_fields[billing_zipcode]" checked="{config.Miscellaneous.partner_profile.billing_zipcode}"></td><td>Zip code</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2><input type=submit value=" Update "></td></tr>
</table>
</form>

<br><br>
<table border=0>
<tr><td colspan=3 class=AdminHead><a name=additional_fields>Registration form additional fields</a></td></tr>
<tr><td colspan=3>&nbsp;</td></tr>
</table>

<!-- fields list -->
<a name=additional_fields></a>
<table FOREACH="xlite.factory.\XLite\Module\CDev\Affiliate\Model\PartnerField.findAll(),pidx,partnerField" border=0>

<form action="admin.php#additional_fields" method=POST>
<input type=hidden name=target value=partner_form>
<input type=hidden name=action value=update_field>
<input type=hidden name=field_id value="{partnerField.field_id}">

<tr><td colspan=3 class=TableHead><b>#{inc(pidx)}: {partnerField.name:r}</b></td</tr>
<tr><td>Field name</td><td width=10 align=center>&nbsp;</td><td><input type=text size=32 name="name" value="{partnerField.name:r}"></td></tr>
<tr valign=top><td>Field value(s)</td><td>&nbsp;</td><td><textarea name=value cols=40 rows=7>{partnerField.value:r}</textarea></td></tr>
<tr>
    <td>Field type</td>
    <td>&nbsp;</td>
    <td>
        <select name=field_type>
            <option value=Text selected="{partnerField.field_type=#Text#}">Text</option>
            <option value=Textarea selected="{partnerField.field_type=#Textarea#}">Textarea</option>
            <option value=SelectBox selected="{partnerField.field_type=#SelectBox#}">SelectBox</option>
            <option value="Radio button" selected="{partnerField.field_type=#Radio button#}">Radio button</option>
            <option value=CheckBox selected="{partnerField.field_type=#CheckBox#}">CheckBox</option>
        </select>
    </td>
</tr>
<tr>
    <td>Field size</td>
    <td>&nbsp;</td>
    <td>
        cols=<input type=text size=2 name=cols value="{partnerField.cols}" maxlength=3>, rows=<input type=text size=2 name=rows value="{partnerField.rows}" maxlength=3>
    </td>
</tr>
<tr><td>Pos.</td><td>&nbsp;</td><td><input type=text size=2 name=orderby value="{partnerField.orderby}" maxlength=3></td></tr>
<tr>
    <td>Required field</td>
    <td>&nbsp;</td>
    <td>
        <select name=required>
            <option value=1 selected="{partnerField.required=#1#}">Yes</option>
            <option value=0 selected="{partnerField.required=#0#}">No</option>
        </select>
    </td>
</tr>
<tr>
    <td>Enabled field</td>
    <td>&nbsp;</td>
    <td>
        <select name=enabled>
            <option value=1 selected="{partnerField.enabled=#1#}">Yes</option>
            <option value=0 selected="{partnerField.enabled=#0#}">No</option>
        </select>
    </td>
</tr>
<tr><td colspan=3><input type=submit name=update value=" Update ">&nbsp;<input type=submit name=delete value=" Delete "></td></tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr><td colspan=3>&nbsp;</td></tr>
</form>
</table>

<!-- /fields list -->


<!-- ADD FORM -->
<form action="admin.php#add_field" method=POST>
<input type=hidden name=target value=partner_form>
<input type=hidden name=action value=add_field>

<a name=add_field></a>
<table border=0>
<tr><td colspan=3 class=AdminTitle>Add field</td</tr>
<tr><td colspan=3 class=AdminTitle>&nbsp;</td</tr>
<tr><td>Field name</td><td class=Star width=10 align=center>*</td><td><input type=text size=32 name="name" value="{name:r}"> <widget class="\XLite\Validator\RequiredValidator" field="name" IF="{action=#add_field#}"></td></tr>
<tr valign=top><td>Field value(s)</td><td>&nbsp;</td><td><textarea name=value cols=40 rows=7>{value:r}</textarea></td></tr>
<tr>
    <td>Field type</td>
    <td>&nbsp;</td>
    <td>
        <select name=field_type>
            <option value=Text selected="{field_type=#Text#}">Text</option>
            <option value=Textarea selected="{field_type=#Textarea#}">Textarea</option>
            <option value=SelectBox selected="{field_type=#SelectBox#}">SelectBox</option>
            <option value="Radio button" selected="{field_type=#Radio button#}">Radio button</option>
            <option value=CheckBox selected="{field_type=#CheckBox#}">CheckBox</option>
        </select>
    </td>
</tr>
<tr>
    <td>Field size</td>
    <td>&nbsp;</td>
    <td>
        <font class=Star>**</font>cols=<input type=text size=2 name=cols value="{cols}" maxlength=3>, <font class=Star>***</font>rows=<input type=text size=2 name=rows value="{rows}" maxlength=3>
    </td>
</tr>
<tr><td>Pos.</td><td>&nbsp;</td><td><input type=text size=2 name=orderby value="{orderby}" maxlength=3></td></tr>
<tr>
    <td>Required field</td>
    <td>&nbsp;</td>
    <td>
        <select name=required>
            <option value=1 selected="{required=#1#}">Yes</option>
            <option value=0 selected="{required=#0#}">No</option>
        </select>
    </td>
</tr>
<tr>
    <td>Enabled field</td>
    <td>&nbsp;</td>
    <td>
        <select name=enabled>
            <option value=1 selected="{enabled=#1#}">Yes</option>
            <option value=0 selected="{enabled=#0#}">No</option>
        </select>
    </td>
</tr>
<tr><td colspan=3><input type=submit value=" Add "></td></tr>

</table>
</form>

<table border=0>
<tr><td colspan=3>&nbsp;</td></tr>
<tr valign=top>
    <th>Note:&nbsp;&nbsp;</th>
    <td><font class=Star>*</font><br>
        <font class=Star>**</font><br>
        <font class=Star>***</font>
   </td>
   <td>- required field,<br>
       - required field for type &quot;Text&quot;,<br>
       - required field for type &quot;Textarea&quot;.
   </td>
</tr>
</table>

<br><br>
