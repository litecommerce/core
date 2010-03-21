{* Checkout credit card form *}
<script language="Javascript" type="text/javascript">
var checkEnabled = parseInt({xlite.config.General.enable_credit_card_validation});
var card_codes = new Array();
{foreach:cart.paymentMethod.cardTypes,key,card}
	card_codes["{card.code}"] = {card.cvv2};	
{end:}

if (isNaN(checkEnabled)) 
	checkEnabled = 0;   

function isCreditCard(cc_number) 
{
	cc = cc_number.value;
	cc = String(cc);
	if(cc.length<4 || cc.length>30)
	{ 
		alert('Credit card number is too short or too long');
		return false; 
	}
	// Start the Mod10 checksum process...
	var checksum=0;
	// Add even digits in even length strings or odd digits in odd length strings.
	for (var location=1-(cc.length%2); location<cc.length; location+=2) 
	{
		var digit=parseInt(cc.substring(location,location+1));
		if(isNaN(digit)) 	
		{
			alert('Credit card number must have only digits');
			return false; 
		}
		checksum+=digit;
	}
	// Analyze odd digits in even length strings or even digits in odd length strings.
	for (var location=(cc.length%2); location<cc.length; location+=2) 
	{
		var digit=parseInt(cc.substring(location,location+1));
		if(isNaN(digit)) 
		{
            alert('Credit card number must have only digits');
			return false;
		}
		if(digit<5) checksum+=digit*2; else checksum+=digit*2-9;
	}

	if(checksum%10!=0) 
	{
        alert('Credit card number\'s checksum is invalid');
		return false;
	}

  	return true;
}

function isCardholderName(cc_name) {
    var cc = cc_name.value;
    cc = String(cc);
    cc = cc.replace(/^\s*/, "").replace(/\s*$/, "");
    if (cc.length == 0) {
        alert("A required field \"Cardholder's name\" has not been completed");
        return false;
    } else {
        return true;
    }
}

function showSoloOrSwitch()
{
	var card_type 		= document.getElementById('cc_type');
	var issue_no 		= document.getElementById('issue_number');
	var start_date		= document.getElementById('start_date');
	var cvv2_label 		= document.getElementById('cvv2_label');

	switch(card_type.value) {
		case 'SO' : 
		case 'SW' :
			issue_no.style.display	 		= '';
			start_date.style.display		= '';					
			break;
		default   : 
			issue_no.style.display		    = 'none';
            start_date.style.display	    = 'none';   
			break;
	}
	if (card_codes[card_type.value] == 0) {
			cvv2_label.style.display = 'none';		
	} else
			cvv2_label.style.display = '';   
	return true;
}

function checkCVV2(cvv2,issue_no)
{
	if (issue_no!=null)	 
	{
		cvv2_text = 'Issue number';
		cvv2 = issue_no.value;
	}
	else 
	{
		cvv2_text = 'CVV2';
		cvv2 = cvv2.value;
	}

	cvv2	= String(cvv2);
	if (cvv2.length == 0) 
	{
		alert(cvv2_text + ' field is empty');
		return false;
	} else if (cvv2.length!=3 && cvv2.length!=4 && issue_no == null)
	{
		alert(cvv2_text +  ' field should be 3- or 4-digit number.');
		return false;
	} 
	for (var i=0; i<cvv2.length; i++) 
	{
		var digit = parseInt(cvv2.substring(i,i+1));
		if (isNaN(digit)) 
		{
			alert(cvv2_text + ' must be a number');
			return false;
		}
	} 
	return true;	
}

function checkStartDate(st_year,st_month,ed_year,ed_month) 
{
	var start_year 	= parseInt(st_year.value.replace(/^0/gi, ""));
    var start_month = parseInt(st_month.value.replace(/^0/gi, ""));
    var exp_year 	= parseInt(ed_year.value.replace(/^0/gi, ""));
    var exp_month 	= parseInt(ed_month.value.replace(/^0/gi, ""));
	if (start_year > exp_year || (start_year==exp_year && start_month > exp_month))
	{
		alert('Start date is older than expiration date');
		return false;
	}
	return true;
}

function checkExpirationDate(ed_month, ed_year) 
{
	var date 			= new Date();
	var current_year 	= parseInt(date.getFullYear());
	var current_month 	= parseInt(date.getMonth())+1;	
       var year = parseInt(ed_year.value.replace(/^0/gi, ""));
   	var month = parseInt(ed_month.value.replace(/^0/gi, ""));
	if(year < current_year || (year == current_year && month < current_month)) 
	{
    alert('This card is expired');
    return false;
	}
	return true;
}

function checkCreditCardInfo()
{
	if (checkEnabled == 0) return true;
	var result;
	var cvv2			= document.getElementById('cc_cvv2');
	var cc_number 		= document.getElementById('cc_number');
	var cc_name 		= document.getElementById('cc_name');
	var issue_no        = document.getElementById('cc_issue');

    var card_type       = document.getElementById('cc_type');
	var st_month		= document.getElementById('cc_info_cc_start_date_Month');
	var st_year	        = document.getElementById('cc_info_cc_start_date_Year');
	var ed_month        = document.getElementById('cc_info_cc_date_Month');
	var ed_year		    = document.getElementById('cc_info_cc_date_Year');

    var issue_box       = document.getElementById('issue_number_box');
    var start_box       = document.getElementById('start_date_box');

	if (!isCreditCard(cc_number)) return false;
    if (!isCardholderName(cc_name)) return false;
    if (!checkExpirationDate(ed_month,ed_year)) return false;
    if (card_type.value == 'SO' || card_type.value == 'SW') 
	{
        if (!start_box.checked && !checkStartDate(st_year,st_month,ed_year,ed_month))  return false;
        if (!issue_box.checked && !checkCVV2(cvv2,issue_no)) return false;
	}
	if (card_codes[card_type.value] == 1) {
		if (!checkCVV2(cvv2)) return false;
	}
	return true;
}

function CheckoutSubmit()
{
    if (checkCreditCardInfo()) {
        var Element = document.getElementById("submit_order_button");
        if (Element) {
            Element.style.display = 'none';
        }

        var Element = document.getElementById("submiting_process");
        if (Element) {
            Element.style.display = "";
            Element.innerHTML = "<b>Please wait while your order is being processed...</b>";
        }

		window.scroll(0, 100000);
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
<input type="hidden" name="action" value="checkout">

<table width="100%" border="0" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle">
    <td height="20" colspan="4"><b>Credit card information</b><hr size=1 noshade></td>
</tr>
<tr valign="middle">
    <td align="right" width="40%">Credit card type</td>
    <td width="10" class="Star">*</td>
    <td>
        <select id="cc_type" name="cc_info[cc_type]" onChange="javascript: showSoloOrSwitch();">
            <option FOREACH="cart.paymentMethod.cardTypes,card" value="{card.code:r}" selected="{isSelected(card,#code#,cart.details.cc_type)}">{card.card_type:h}</option>
        </select>
    </td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">Cardholder's name</td>
    <td class="Star">*</td>
    <td><input type="text" name="cc_info[cc_name]" id="cc_name" value="{cart.profile.billing_firstname} {cart.profile.billing_lastname}" size="32" maxlength="255"></td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">Credit card number (no spaces or dashes)</td>
    <td class="Star">*</td>
    <td><input type="text" name="cc_info[cc_number]" id='cc_number' value="{cart.details.cc_number:r}" size="32" maxlength="20"></td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr id='start_date' valign="top">
    <td align="right">Start date (MMYY)</td>
    <td class="Star"><span id="start_date_star">*</span></td>
    <td>
        <widget class="XLite_View_Date" field="cc_info_cc_start_date_" hide_days="1" higherYear="{currentYear}" showMonthsNumbers="1">
        <br><input type="checkbox" name="no_start_date" id="start_date_box" onclick="javascript: showStar('start_date');">&nbsp;<label for="start_date_box">My card has no "Start date" information</label>
        <input type="hidden" name="cc_info[cc_start_date]" value="MMYY">
    </td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">Expiration date (MMYY)</td>
    <td class="Star">*</td>
    <td><widget class="XLite_View_Date" field="cc_info_cc_date_" hide_days="1" lowerYear="{currentYear}" yearsRange="5" showMonthsNumbers="1"><input type="hidden" name="cc_info[cc_date]" value="MMYY"></td>
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
    <td align="right">Credit Card Code (CVV2/CVC2/CID)<br>
	<i>A three- or four-digit security code that
	is printed on the back of credit cards in reverse italics 
	in the card's signature panel (or on the front for American 
	Express cards).</i>
	</td>
    <td valign="top"><span id="cvv2_label" class="Star">*</span></td>
    <td valign="top"><input type="text" id='cc_cvv2' name="cc_info[cc_cvv2]" value="{cart.details.cc_cvv2:r}" size="4" maxlength="4"></td>
    <td colspan="2">&nbsp;</td>
</tr>
<script language="Javascript" type="text/javascript">
	showSoloOrSwitch();	
</script>
<widget module="CardinalCommerce" template="modules/CardinalCommerce/credit_card.tpl" visible="{SupportedByCardinalCommerce}">
</table>

<p>
<b>Notes</b><br>
<hr>
<table border="0" width="100%">
<tr>
    <td valign="top" align="right" width="40%">Customer notes:&nbsp;</td>
    <td align="left">&nbsp;<textarea cols="50" rows="7" name="notes"></textarea></td>
</tr>
</table>
</p>

<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&amp;mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&amp;mode=privacy_statement'><u>Privacy statement</u></a>".<br>
<br>
<span id="submit_order_button">
<widget class="XLite_View_Button_Regular" label="Submit order" jsCode="CheckoutSubmit();" />
</span>
<span id="submiting_process" style="display:none"></span>
</form>
