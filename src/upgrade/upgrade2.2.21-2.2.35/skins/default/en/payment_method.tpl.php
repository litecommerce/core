<?php

	$find_str = <<<EOT
<script language="JavaScript">
function continueCheckout()
{
	if (!document.payment_method.payment_id) {
EOT;
	$replace_str = <<<EOT
<script language="JavaScript" type="text/javascript">
function continueCheckout()
{
	if (!document.payment_method.payment_id) {
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
