/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Check credit card data
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id: check_cc.js 2678 2010-04-20 06:11:11Z max $
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

function isCreditCard(cc_number) 
{
  cc = cc_number.value.replace(/\D/, '');
  cc = String(cc);
  if (cc.length < 4 || cc.length > 30) { 
    alert('Credit card number is too short or too long');
    return false; 
  }

  // Start the Mod10 checksum process...
  var checksum = 0;

  // Add even digits in even length strings or odd digits in odd length strings.
  for (var location = 1 - (cc.length % 2); location < cc.length; location += 2) 
  {
    var digit = parseInt(cc.substring(location, location + 1));
    if (isNaN(digit)) {
      alert('Credit card number must have only digits');
      return false; 
    }

    checksum += digit;
  }

  // Analyze odd digits in even length strings or even digits in odd length strings.
  for (var location = (cc.length % 2); location < cc.length; location += 2) {
    var digit = parseInt(cc.substring(location, location + 1));
    if (isNaN(digit)){
      alert('Credit card number must have only digits');
      return false;
    }

    checksum += digit < 5 ? digit * 2 : digit * 2 - 9;
  }

  if (checksum % 10 != 0) {
    alert('Credit card number\'s checksum is invalid');
    return false;
  }

  return true;
}

function isCardholderName(cc_name)
{
  var cc = cc_name.value;
  cc = String(cc);
  cc = cc.replace(/^\s*/, '').replace(/\s*$/, '');

  if (cc.length == 0) {
    alert('A required field "Cardholder\'s name" has not been completed');
  }

  return cc.length != 0;
}

function showSoloOrSwitch()
{
  var card_type  = document.getElementById('cc_type');
  var issue_no   = document.getElementById('issue_number');
  var start_date = document.getElementById('start_date');
  var cvv2_label = document.getElementById('cvv2_label');

  switch(card_type.value) {
    case 'SO': 
    case 'SW':
      issue_no.style.display   = '';
      start_date.style.display = '';          
      break;

    default: 
      issue_no.style.display   = 'none';
      start_date.style.display = 'none';   
      break;
  }

  cvv2_label.style.display = card_codes[card_type.value] == 0 ? 'none' : '';

  return true;
}

function checkCVV2(cvv2, issue_no)
{
  if (issue_no != null) {
    cvv2_text = 'Issue number';
    cvv2 = issue_no.value;

  } else {
    cvv2_text = 'CVV2';
    cvv2 = cvv2.value;
  }

  cvv2  = String(cvv2);
  if (cvv2.length == 0) {
    alert(cvv2_text + ' field is empty');
    return false;

  } else if (cvv2.length!=3 && cvv2.length!=4 && issue_no == null) {
    alert(cvv2_text +  ' field should be 3- or 4-digit number.');
    return false;
  }

  for (var i = 0; i < cvv2.length; i++) {
    var digit = parseInt(cvv2.substring(i, i + 1));
    if (isNaN(digit)) {
      alert(cvv2_text + ' must be a number');
      return false;
    }
  } 

  return true;  
}

function checkStartDate(st_year, st_month, ed_year, ed_month) 
{
  var start_year  = parseInt(st_year.value.replace(/^0/gi, ''));
  var start_month = parseInt(st_month.value.replace(/^0/gi, ''));
  var exp_year    = parseInt(ed_year.value.replace(/^0/gi, ''));
  var exp_month   = parseInt(ed_month.value.replace(/^0/gi, ''));

  if (start_year > exp_year || (start_year == exp_year && start_month > exp_month)) {
    alert('Start date is older than expiration date');
    return false;
  }

  return true;
}

function checkExpirationDate(ed_month, ed_year) 
{
  var date          = new Date();
  var current_year  = parseInt(date.getFullYear());
  var current_month = parseInt(date.getMonth()) + 1;
  var year          = parseInt(ed_year.value.replace(/^0/gi, ''));
  var month         = parseInt(ed_month.value.replace(/^0/gi, ''));

  if (year < current_year || (year == current_year && month < current_month)) {
    alert('This card is expired');
    return false;
  }

  return true;
}

function checkCreditCardInfo()
{
  if (checkEnabled == 0) {
    return true;
  }

  var result;
  var cvv2      = document.getElementById('cc_cvv2');
  var cc_number = document.getElementById('cc_number');
  var cc_name   = document.getElementById('cc_name');
  var issue_no  = document.getElementById('cc_issue');
  var card_type = document.getElementById('cc_type');
  var st_month  = document.getElementById('cc_info_cc_start_date_Month');
  var st_year   = document.getElementById('cc_info_cc_start_date_Year');
  var ed_month  = document.getElementById('cc_info_cc_date_Month');
  var ed_year   = document.getElementById('cc_info_cc_date_Year');

  var issue_box = document.getElementById('issue_number_box');
  var start_box = document.getElementById('start_date_box');

  if (!isCreditCard(cc_number) || !isCardholderName(cc_name) || !checkExpirationDate(ed_month,ed_year)) {
    return false;
  }

  if (card_type.value == 'SO' || card_type.value == 'SW') {
    if (
      (!start_box.checked && !checkStartDate(st_year,st_month,ed_year,ed_month))
      || (!issue_box.checked && !checkCVV2(cvv2,issue_no))
    ) {
      return false;
    }
  }

  if (card_codes[card_type.value] == 1 && !checkCVV2(cvv2)) {
    return false;
  }

  return true;
}
