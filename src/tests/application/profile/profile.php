<?php


// check authentication with incorrect parameters
$post = array("login" => "tester", "password" => "incorrect");
sendRequest("login", "login", null, $post);

// check for authentication widget
$w =& Widget::getByName("Authentication");
assert($w->status == ENABLED);

// request registration form
sendRequest("profile", "register");
$w =& Widget::getByName("Register");
assert($w->status == ENABLED);

// login with valid auth info
$post = array("login" => "bit-bucket@rrf.ru", "password" => "123");
sendRequest("login", "login", null, $post);
assert($session->isRegistered("profile_id") && $session->getVar("profile_id") == 1);

// check logoff
sendRequest("login", "logoff");
assert(!$session->isRegistered("profile_id"));

$error_reporting = error_reporting(E_ALL ^ E_NOTICE);

// attempt to register with insufficient parameters
$registration_form["login"] = "bitbucket@rrf.ru";
$post = array("form" => "registration_form", "registration_form" => $registration_form);
sendRequest("profile", "register", null, $post);
$w =& Widget::getByName("Register");
assert($w->status == ENABLED);

// logoff profile (if logged)
$auth =& Auth::getInstance();
if ($auth->isLogged()) {
    $auth->logoff();
}
// cleanup database from test data
$profile =& new Profile;
if ($profile->find("login='bitbucket@rrf.ru'")) {
    $profile->delete();
}

// attempt to register with correct parameters
$registration_form[login] = "bitbucket@rrf.ru";
$registration_form[password] = "123";
$registration_form[confirm_password] = "123";
$registration_form[billing_firstname] = "Bit"; 
$registration_form[billing_lastname] = "Bucket"; 
$registration_form[billing_phone] = "123";
$registration_form[billing_address] = "Test street";
$registration_form[billing_city] = "Edmond";
$registration_form[billing_state] = "OK";
$registration_form[billing_country] = "US";
$registration_form[billing_zipcode] = "12345";

$post = array("form" => "registration_form", "registration_form" => $registration_form);

sendRequest("profile", "register", null, $post);
$profile = new Profile();
assert($profile->find("login='bitbucket@rrf.ru'") && $session->isRegistered("profile_id"));

// attempt to modify profile
$registration_form[password] = "321";
$registration_form[confirm_password] = "321";
$post = array("form" => "profile_form", "profile_form" => $registration_form);
sendRequest("profile", "modify", null, $post);
$w =& Widget::getByName("Profile");
assert($w->status == ENABLED && $w->dialog->success);

// attempt to modify profile with insufficient data
$registration_form[billing_address] = "";
sendRequest("profile", "modify", null, $post);
$w =& Widget::getByName("Profile");
assert($w->status == ENABLED && $w->dialog->validate);

// attempt to delete profile
$get = array("mode" => "confirmed");
sendRequest("profile", "delete", $get);
assert(!$profile->find("login='bitbucket@rrf.ru'") && !$session->isRegistered("profile_id"));

// attempt to register with duplicate login (email);
$registration_form["login"] = "bit-bucket@rrf.ru";
$registration_form[billing_address] = "Test street, 1";
$post = array("form" => "registration_form", "registration_form" => $registration_form);
sendRequest("profile", "register", null, $post);
$w =& Widget::getByName("Register");
assert($w->status == ENABLED && $w->dialog->userExists);

error_reporting($error_reporting);
?>
