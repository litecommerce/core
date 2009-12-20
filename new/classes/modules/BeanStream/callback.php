<?php

$_REQUEST["target"] = $_POST["target"] = "beanstream_checkout";
$_REQUEST["action"] = $_POST["action"] = "return";

chdir("../../../");
include "cart.php";

?>
