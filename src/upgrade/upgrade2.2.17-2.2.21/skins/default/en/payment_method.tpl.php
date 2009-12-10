<?php

	$find_str = "<font color=\"red\" IF=\"error=#pmSelect#\">To proceed with checkout, you need to select a payment method.</font>";
    $replace_str = "<span class=\"ErrorMessage\" IF=\"error=#pmSelect#\">To proceed with checkout, you need to select a payment method.</span>";
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>