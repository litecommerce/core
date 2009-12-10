<?php

$source = strReplace('<script>', '<script language="JavaScript">'."\n".'<!--', $source, __FILE__, __LINE__);
$source = strReplace('</script>', '// -->'."\n".'</script>'."\n", $source, __FILE__, __LINE__);
$source = strReplace('<input type="hidden" name="target" value="help">', '<input type="hidden" foreach="params,param" name="{param}" value="{get(param):r}"/>', $source, __FILE__, __LINE__);
$source = strReplace('<input type=text name=email size=32 maxlength=128 value="{profile.login:r}">', '<input type=text name=email size=32 maxlength=128 value="{email:r}">', $source, __FILE__, __LINE__);
$source = strReplace('<input type=text name=firstname size=32 maxlength=32 value="{profile.billing_firstname:r}">', '<input type=text name=firstname size=32 maxlength=32 value="{firstname:r}">', $source, __FILE__, __LINE__);
$source = strReplace('<input type=text name=lastname size=32 maxlength=32 value="{profile.billing_lastname:r}">', '<input type=text name=lastname size=32 maxlength=32 value="{lastname:r}">', $source, __FILE__, __LINE__);
$source = strReplace('<input type=text name=b_address size=32 maxlength=64 value="{profile.billing_address:r}">', '<input type=text name=address size=32 maxlength=64 value="{address:r}">', $source, __FILE__, __LINE__);
$source = strReplace('<input type=text name=b_zipcode size=32 maxlength=32 value="{profile.billing_zipcode:r}">', '<input type=text name=zipcode size=32 maxlength=32 value="{zipcode:r}">', $source, __FILE__, __LINE__);
$source = strReplace('<input type=text name=b_city size=32 maxlength=64 value="{profile.billing_city:r}">', '<input type=text name=city size=32 maxlength=64 value="{city:r}">', $source, __FILE__, __LINE__);
$source = strReplace('{state.display()}', '<widget class="CStateSelect" field="state_id">', $source, __FILE__, __LINE__);
$source = strReplace('{country.display()}', '<widget class="CCountrySelect" field="country_id">', $source, __FILE__, __LINE__);
$source = strReplace('<input type=text name=phone size=32 maxlength=32 value="{profile.billing_phone:r}">', '<input type=text name=phone size=32 maxlength=32 value="{phone:r}">', $source, __FILE__, __LINE__);
$source = strReplace('<input type=text name=fax size=32 maxlength=128 value="{profile.billing_fax:r}"></td>', '<input type=text name=fax size=32 maxlength=128 value="{fax:r}"></td>', $source, __FILE__, __LINE__);
$source = strReplace('<input type=text name=subject size=32 maxlength=128 value="">', '<input type=text name=subj size=32 maxlength=128 value="">', $source, __FILE__, __LINE__);

?>
