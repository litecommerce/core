<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
* $Id: script.php,v 1.3 2007/03/19 11:05:09 sheriff Exp $
*/

// reads configuration files
include_once "includes/prepend.php";
set_time_limit(10000);
header("Content-type: text/plain");

// defines interface
$GLOBALS["XLITE_SELF"] = "cart.php";

// creates cart instance
$xlite =& func_get_instance("XLite");
// dummy view
$w =& func_new("Widget");
$w->set("name", "Main");
$xlite->initFromGlobals();

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
