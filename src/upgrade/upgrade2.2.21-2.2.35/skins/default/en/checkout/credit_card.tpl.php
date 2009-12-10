<?php

	$find_str = <<<EOT
	var ed_month        = document.getElementById('cc_info_cc_date_Month');
	var ed_year		    = document.getElementById('cc_info_cc_date_Year');

	if (!isCreditCard(cc_number)) return false; 
	if (!checkExpirationDate(ed_month,ed_year)) return false;
	if (card_type.value == 'SO' || card_type.value == 'SW') 
	{
		if (!checkStartDate(st_year,st_month,ed_year,ed_month))  return false;
		if (!checkCVV2(cvv2,issue_no)) return false;
	}
	if (card_codes[card_type.value] == 1) {
		if (!checkCVV2(cvv2)) return false;
EOT;

	$replace_str = <<<EOT
	var ed_month        = document.getElementById('cc_info_cc_date_Month');
	var ed_year		    = document.getElementById('cc_info_cc_date_Year');

    var issue_box       = document.getElementById('issue_number_box');
    var start_box       = document.getElementById('start_date_box');

	if (!isCreditCard(cc_number)) return false; 
    if (!checkExpirationDate(ed_month,ed_year)) return false;
    if (card_type.value == 'SO' || card_type.value == 'SW') 
	{
        if (!start_box.checked && !checkStartDate(st_year,st_month,ed_year,ed_month))  return false;
        if (!issue_box.checked && !checkCVV2(cvv2,issue_no)) return false;
	}
	if (card_codes[card_type.value] == 1) {
		if (!checkCVV2(cvv2)) return false;
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
        document.checkout.submit();
    }
}
</script>
<form name="checkout" action="cart.php" method="POST">
<input type="hidden" name="target" value="checkout">
EOT;

	$replace_str = <<<EOT
        document.checkout.submit();
    }
}

function showStar(name)
{
    if (typeof(name) == "undefined") {
        return false;
    }
  
    var box  = document.getElementById(name + "_box");
    var star = document.getElementById(name + "_star");

    if (box && star) {
        if (box.checked) {
            star.style.display = "none";
        } else {
            star.style.display = "";
        }
    }

    return true;
}
</script>
<form name="checkout" action="cart.php" method="POST">
<input type="hidden" name="target" value="checkout">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
    <td><input type="text" name="cc_info[cc_number]" id='cc_number' value="{cart.details.cc_number:r}" size="32" maxlength="20"></td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr id='start_date' valign="middle">
    <td align="right">Start date (MMYY)</td>
    <td class="Star">*</td>
    <td><widget class="CDate" field="cc_info_cc_start_date_" hide_days="1" higherYear="{currentYear}" showMonthsNumbers="1"><input type="hidden" name="cc_info[cc_start_date]" value="MMYY"></td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr valign="middle">
EOT;

	$replace_str = <<<EOT
    <td><input type="text" name="cc_info[cc_number]" id='cc_number' value="{cart.details.cc_number:r}" size="32" maxlength="20"></td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr id='start_date' valign="top">
    <td align="right">Start date (MMYY)</td>
    <td class="Star"><span id="start_date_star">*</span></td>
    <td>
        <widget class="CDate" field="cc_info_cc_start_date_" hide_days="1" higherYear="{currentYear}" showMonthsNumbers="1">
        <br><input type="checkbox" name="no_start_date" id="start_date_box" onclick="javascript: showStar('start_date');">&nbsp;<label for="start_date_box">My card has no "Start date" information</label>
        <input type="hidden" name="cc_info[cc_start_date]" value="MMYY">
    </td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr valign="middle">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
    <td><widget class="CDate" field="cc_info_cc_date_" hide_days="1" lowerYear="{currentYear}" yearsRange="5" showMonthsNumbers="1"><input type="hidden" name="cc_info[cc_date]" value="MMYY"></td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr id='issue_number' valign="middle">
    <td align="right">Issue no.</td>
    <td valign="top" class="Star">*</td>
    <td valign="top"><input type="text" id="cc_issue" name="cc_info[cc_issue]" value="{cart.details.cc_issue:r}" size="4" maxlength="4"></td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr valign="middle">
EOT;

	$replace_str = <<<EOT
    <td><widget class="CDate" field="cc_info_cc_date_" hide_days="1" lowerYear="{currentYear}" yearsRange="5" showMonthsNumbers="1"><input type="hidden" name="cc_info[cc_date]" value="MMYY"></td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr id='issue_number' valign="top">
    <td align="right">Issue no.</td>
    <td class="Star"><span id="issue_number_star">*</span></td>
    <td>
        <input type="text" id="cc_issue" name="cc_info[cc_issue]" value="{cart.details.cc_issue:r}" size="4" maxlength="4">
        <br><input type="checkbox" name="no_issue_number" id="issue_number_box" onclick="javascript: showStar('issue_number');">&nbsp;<label for="issue_number_box">My card has no "Issue no." information</label>
    </td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr valign="middle">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
