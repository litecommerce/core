<?php
if (isset($_SERVER['HTTP_REFERER'])) $_POST['RESULT'] = 1;
header("Location: ../../../cart.php?target=callback&action=callback&order_id=".urlencode($_POST['INVOICE'])."&resp=".urlencode($_POST['RESPMSG'])."&result=".urlencode($_POST['RESULT'])."&acode=".urlencode($_POST['AUTHCODE'])."&ref=".urlencode($_POST['PNREF']));
?>
