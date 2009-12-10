<?php

	$find_str = <<<EOT
<script language="Javascript">
function CheckoutSubmit()
{
    var Element = document.getElementById("submit_order_button");
EOT;

	$replace_str = <<<EOT
<script language="Javascript" type="text/javascript">
function CheckoutSubmit()
{
    var Element = document.getElementById("submit_order_button");
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
