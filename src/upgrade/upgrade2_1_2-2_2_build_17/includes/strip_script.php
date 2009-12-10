<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

foreach ($_REQUEST as $key => $value) {
    // checking get parameters only
    if (!isset($_GET[$key])) {
        continue;
    }
    if (hasScript($value)) {
        if (isset($_REQUEST[$key])) {
        	unset($_REQUEST[$key]);
        }
        if (isset($_GET[$key])) {
        	unset($_GET[$key]);
        }
    }
}

function hasScript($variable)
{
    if (is_array($variable)) {
        foreach ($variable as $key=>$value) {
            if (hasScript($value)) {
                return true;
            }
        }
    } else {
        $variable = urldecode($variable);
        if (strpos(strtoupper($variable), "<SCRIPT") !== false) {
            return true;
        }
    }
    return false;
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
