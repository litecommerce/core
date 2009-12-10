<?php

$source = strReplace('<hr size="1" noshade>'."\n", '<widget module="Promotion" template="modules/Promotion/bonus_points.tpl"><p>', $source, __FILE__, __LINE__);
$source = strReplace('<form action="cart.php" method="post" name="profile_form">'."\n".'<table width="100%" border="0" cellspacing="0" cellpadding="2">', '<form action="cart.php" method="POST" name="profile_form">'."\n<table>", $source, __FILE__, __LINE__);

$search =<<<EOT
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;The user {login} is already registered! Please choose another e-mail.&nbsp;&lt;&lt;</td>
</tr>

<tr valign="middle">
    <td colspan="4">&nbsp;</td>
</tr>
EOT;

$replace =<<<EOT
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;The user {login} is already registered! Please choose another e-mail.&nbsp;&lt;&lt;</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

$search =<<<EOT
        &nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addRequiredValidator(#login#,requiredFieldError)}" requiredFieldError="<< Required field" alt="E-mail required error message">
            <img src="images/code.gif" code="{addPatternValidator(#/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/#,wrongEmailError)}" wrongEmailError="<< Please, enter valid e-mail address" alt="E-mail is wrong error message">
        </font>
EOT;

$source = strReplace($search, '<widget class="CEmailValidator" field="login">', $source, __FILE__, __LINE__);

$search =<<<EOT
&nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addPasswordValidator(#confirm_password#,#password#,passwordError)}" passwordError="<< Confirmation does not match" alt="Password confirmation error message" >
        </font>
EOT;

$source = strReplace($search, '<widget class="CPasswordValidator" field="confirm_password" passwordField="password">', $source, __FILE__, __LINE__);

// replace <select> options
$source = preg_replace("/<option value=\"(\w+\.)\" selected=\"{isSelected\(#\w+\.#,(\w+)\)}\">\w+\.<\/option>/",
                       "<option value=\"\\1\" selected=\"{\\2=#\\1#}\">\\1</option>",
                       $source);                       

$search =<<<EOT
&nbsp;<font class="validateerrormessage">
            <img src="images/code.gif" code="{addrequiredvalidator(#billing_firstname#,requiredfielderror)}" requiredfielderror="<< Required field" alt="first name error message">
        </font>
EOT;

$source = strReplace($search, '<widget class="CRequiredValidator" field="billing_firstname">', $source, __FILE__, __LINE__);

$search =<<<EOT
&nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addRequiredValidator(#billing_lastname#,requiredFieldError)}" requiredFieldError="<< Required field" alt="Last name error message">
        </font>
EOT;

$source = strReplace($search, '<widget class="CRequiredValidator" field="billing_lastname">', $source, __FILE__, __LINE__);

$search =<<<EOT
&nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addRequiredValidator(#billing_phone#,requiredFieldError)}" requiredFieldError="<< Required field" alt="Phone error message">
        </font>
EOT;

$source = strReplace($search, '<widget class="CRequiredValidator" field="billing_phone">', $source, __FILE__, __LINE__);

$search =<<<EOT
&nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addRequiredValidator(#billing_address#,requiredFieldError)}" requiredFieldError="<< Required field" alt="Address error message">
        </font>
EOT;

$source = strReplace($search, '<widget class="CRequiredValidator" field="billing_address">', $source, __FILE__, __LINE__);

$search =<<<EOT
&nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addRequiredValidator(#billing_city#,requiredFieldError)}" requiredFieldError="<< Required field" alt="City error message">
        </font>
EOT;

$source = strReplace($search, '<widget class="CRequiredValidator" field="billing_city">', $source, __FILE__, __LINE__);

// state / country widgets
$source = preg_replace("/{(billing_state|shipping_state)\.display\(\)}/", "<widget class=\"CStateSelect\" field=\"\\1\">", $source);
$source = preg_replace("/{(billing_country|shipping_country)\.display\(\)}/", "<widget class=\"CCountrySelect\" field=\"\\1\">", $source);

$search =<<<EOT
&nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addStateValidator(#billing_state#,#billing_country#,stateFieldError)}" stateFieldError="<< State doesn't match country" alt="State doesn't match country error message">
        </font>
EOT;

$replace =<<<EOT
<widget class="CRequiredValidator" field="billing_state">
<widget class="CStateValidator" field="billing_state" countryField="billing_country">
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

$search =<<<EOT
&nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addRequiredValidator(#billing_country#,requiredFieldError)}" requiredFieldError="<< Required field" alt="Country error message">
        </font>
EOT;

$source = strReplace($search, '<widget class="CRequiredValidator" field="billing_country">', $source, __FILE__, __LINE__);

$search =<<<EOT
&nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addRequiredValidator(#billing_zipcode#,requiredFieldError)}" requiredFieldError="<< Required field" alt="Zip code error message">
        </font>
EOT;

$source = strReplace($search, '<widget class="CRequiredValidator" field="billing_zipcode">', $source, __FILE__, __LINE__);

$source = strReplace('{pending_membership.display()}', '<widget class="CMembershipSelect" field="pending_membership">', $source, __FILE__, __LINE__);

$source = strReplace('{extraFields.display()}', '{*extraFields*}', $source, __FILE__, __LINE__);

$source = strReplace('By clicking "SUBMIT" you are agree with our <a href="cart.php?target=help&action=conditions" style="TEXT-DECORATION: underline">Terms &amp; Conditions.</a><br>', 'By clicking "SUBMIT" you are agree with our <a href="cart.php?target=help&mode=terms_conditions" style="TEXT-DECORATION: underline" target="_blank">Terms &amp; Conditions.</a><br>', $source, __FILE__, __LINE__);

$source = strReplace('<input type="hidden" name="target" value="profile">', '<input type="hidden" foreach="dialog.allparams,param,v" name="{param}" value="{v}"/>', $source, __FILE__, __LINE__);
$source = strReplace('<input type="hidden" name="action" value="modify">', '<input type="hidden" name="action" value="{mode}">', $source, __FILE__, __LINE__);
$source = strReplace('<input type="hidden" name="form"   value="profile_form">', '', $source, __FILE__, __LINE__);

?>
