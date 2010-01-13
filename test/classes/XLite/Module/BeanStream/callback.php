<?php

$_REQUEST["target"] = $_POST["target"] = "beanstream_checkout";
$_REQUEST["action"] = $_POST["action"] = "return";

//FIXME - is this needed?
// chdir("../../../");

include LC_ROOT_DIR . 'cart.php';

?>
