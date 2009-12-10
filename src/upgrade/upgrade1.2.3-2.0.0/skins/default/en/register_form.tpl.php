<?php

$search =<<<EOT
&nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addRequiredValidator(#login#,requiredFieldError)}" requiredFieldError="<< Required field" alt="E-mail required error message">
            <img src="images/code.gif" code="{addPatternValidator(#/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/#,wrongEmailError)}" wrongEmailError="<< Please, enter valid e-mail address" alt="E-mail is wrong error message">
        </font>
EOT;

$source = strReplace($search, '<widget class="CEmailValidator" field="login">', $source, __FILE__, __LINE__);

$source = strReplace('<tr valign="middle" IF="checkout">', '<tr valign="middle" IF="allowAnonymous">', $source, __FILE__, __LINE__);
$source = strReplace('<td cospan="2">', '<td colspan="2">', $source, __FILE__, __LINE__);

$search =<<<EOT
<span IF="!checkout">
		<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addRequiredValidator(#password#,requiredFieldError)}" requiredFieldError="<< Required field" alt="Password required error message">
        </font>
		</span>
EOT;

$source = strReplace($search, '<widget class="CRequiredValidator" field="password" visible="{!allowAnonymous}">', $source, __FILE__, __LINE__);

$search =<<<EOT
<span IF="!checkout">
		<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addPasswordValidator(#confirm_password#,#password#,passwordError)}" passwordError="<< Confirmation does not match" alt="Password confirmation error message" >
            <img src="images/code.gif" code="{addRequiredValidator(#confirm_password#,requiredFieldError)}" requiredFieldError="<< Required field" alt="Password required error message">
        </font>
		</span>
EOT;

$replace =<<<EOT
<widget class="CRequiredValidator" field="confirm_password" visible="{!allowAnonymous}">
<widget class="CPasswordValidator" field="confirm_password" passwordField="password" visible="{!allowAnonymous}">
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

// replace <select> options
$source = preg_replace("/<option value=\"(\w+\.)\" selected=\"{isSelected\(#\w+\.#,(\w+)\)}\">\w+\.<\/option>/",
                       "<option value=\"\\1\" selected=\"{\\2=#\\1#}\">\\1</option>",
                                              $source);

// state / country widgets
$source = preg_replace("/{(billing_state|shipping_state)\.display\(\)}/", "<widget class=\"CStateSelect\" field=\"\\1\">", $source);
$source = preg_replace("/{(billing_country|shipping_country)\.display\(\)}/", "<widget class=\"CCountrySelect\" field=\"\\1\">", $source);

$search =<<<EOT
&nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addRequiredValidator(#billing_firstname#,requiredFieldError)}" requiredFieldError="<< Required field" alt="First name error message">
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

$search =<<<EOT
&nbsp;<font class="ValidateErrorMessage">
            <img src="images/code.gif" code="{addStateValidator(#shipping_state#,#shipping_country#,stateFieldError)}" stateFieldError="<< State doesn't match country" alt="State doesn't match country error message">
        </font>
EOT;

$source = strReplace($search, '<widget class="CStateValidator" field="shipping_state" countryField="shipping_country">', $source, __FILE__, __LINE__);

$source = strReplace('<td>{pending_membership.display()}', '<td><widget class="CMembershipSelect" field="pending_membership">', $source, __FILE__, __LINE__);
$source = strReplace('{extraFields.display()}', '{*extraFields*}', $source, __FILE__, __LINE__);
$source = strReplace('By clicking "SUBMIT" you are agree with our <a href="cart.php?target=help&action=conditions" style="TEXT-DECORATION: underline">Terms &amp; Conditions.</a><br', 'By clicking "SUBMIT" you are agree with our <a href="cart.php?target=help&mode=terms_conditions" style="TEXT-DECORATION: underline" target="_blank">Terms &amp; Conditions.</a><br>', $source, __FILE__, __LINE__);
$source = strReplace('<input type="hidden" name="target" value="{target}">', '<input type="hidden" foreach="dialog.allparams,param,v" name="{param}" value="{v}"/>', $source, __FILE__, __LINE__);
$source = strReplace('<input type="hidden" name="action" value="{action}">', '<input type="hidden" name="action" value="{mode}">', $source, __FILE__, __LINE__);
$source = strReplace('<input type="hidden" name="form"   value="registration_form">', '', $source, __FILE__, __LINE__);

?>
