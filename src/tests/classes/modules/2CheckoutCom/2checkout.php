x_invoice_num = <?php echo $x_invoice_num; ?><br>
x_login = <?php echo $x_login; ?> <br>
<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

if (!isset($x_response_code)) {
    $x_response_code = "1";
}    
$data = array_merge($_POST, array(
            'x_2checked' =>  'Y',
            'x_MD5_Hash' => $md5,
            'x_response_code' => $x_response_code,
            'x_response_subcode' => 1,
            'x_response_reason_code' =>  1,
            'x_auth_code' => '123456',
            'x_avs_code' => 'P',
            'x_trans_id' => $order_number,
            ));
?>
<form action="2checkout.php" method="POST">
<?  
    foreach ($_POST as $key => $value) {
?>
<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
<?
    }
?>
return url = <input type="test" name="return_url" value="<?php echo $return_url; ?>" size="50">
<input type="submit">
</form>
<form action="<?php echo $return_url; ?>" method="POST">
<?
    foreach ($data as $key => $value) {
?>
<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
<?
    }
?>
<select name="x_response_code">
<option value="1">Success
<option value="0">Failure
</select>
<input type="submit">
</form>
