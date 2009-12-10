<?php

/*
* $Id: recorder.php,v 1.3 2007/05/21 11:53:29 osipov Exp $
*/

if (isset($_REQUEST["target"]) && $_REQUEST["target"] != "callback_check" && $_REQUEST["target"] != "image") {
    $fd = @fopen ("var/log/RECORDER", "a");
    if ($fd) {
    	@fwrite($fd, '$this->request("' . basename($PHP_SELF) . '",' . func_to_php($_GET) . ',' . func_to_php($_POST) . ");\n");
    	@fwrite($fd, "\$this->assertRE('//');\n");
    	@fclose($fd);
    }
}

function func_to_php($arr)
{
    $result = 'array(';
    foreach ($arr as $name => $val) {
        if (is_array($val)) $val = func_to_php($val);
        else $val = '"' . $val . '"';
        $result .= '"' . $name . '" => ' . $val . ',';
    }
    return $result . ')';
}
?>
