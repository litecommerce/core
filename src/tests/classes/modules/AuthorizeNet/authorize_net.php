<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/* test login: test
   test traqnsaction key: test
   cc# : 4111111111111111
   card code: 123
   amount must be greater than $100
   secure word: test
*/
foreach ($_POST as $key => $val) {
    $_POST[$key] = stripslashes($val);
    $$key = stripslashes($val);
}
check_fields('x_Version','x_Delim_Data','x_Login','x_Tran_Key','x_Amount','x_Type');
$response[1] = "1";
if ($x_Login != 'test' || $x_Tran_Key != 'test')
{
    $response[1] = "3";
    $response[4] = "This transaction cannot be accepted.";
} else {
    if ($x_Method == 'CC') {
        if ($x_Amount<100 || $x_Card_Num != '4111111111111111' || $x_Test_Request != 'TRUE') {
            $response[1] = "2";
            if ($x_Amount<100) $response[4] = "amount < 100";
            else if($x_Card_Num != '4111111111111111') $response[4] = "CC# != 4111111111111111";
            else if($x_Test_Request != 'TRUE') $response[4] = "Not a test transaction";
        } else {
            if ($x_Card_Code && $x_Card_Code != '123') {
                $response[1] = "3";
                $response[39] = 'N';
            } else {
                $response[1] = "1";
                $response[39] = 'M';
            }
        }
    } else if ($x_Method == 'ECHECK') {
        check_fields('x_Bank_ABA_Code','x_Bank_Acct_Num','x_Bank_Acct_Type','x_Bank_Name','x_Bank_Acct_Name');
        if ($x_Bank_ABA_Code!='x_Bank_ABA_Code' || $x_Bank_Acct_Num!='x_Bank_Acct_Num' || $x_Bank_Acct_Type!='CHECKING' || $x_Bank_Name!='x_Bank_Name' || $x_Bank_Acct_Name!='x_Bank_Acct_Name') {
            $response[1] = "2";
            $response[4] = "Wrong Echeck value passed";
        }
    } else {
        $response[1] = "2";
        $response[4] = "Declined";
    }
}
// md5
$response[7] = $transid = 123;
$response[38] = md5('testtest' . $transid . sprintf("%.2f", $x_Amount));
for ($i=1; $i<40; $i++) {
    print $x_Encap_Char.$response[$i].$x_Encap_Char;
    print $x_ADC_Delim_Character;
}

function check_fields()
{
    global $response;
    $fields = func_get_args();
    foreach($fields as $field) {
        global $$field;
        if (empty($$field)) {
            $response[1] = "2";
            $response[4] = "Field $field must be set";
        }
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
