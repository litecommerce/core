<?php

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

define("IONCUBE_ENCODER", "\"C:/Program Files/ionCube PHP Encoder 6.0/ioncube_encoder.exe\"");

unset($action);
$action = null;

unset($errStatus);
$errStatus = null;

switch(strtoupper($_SERVER["REQUEST_METHOD"]))
{
	case "GET":
		$REQUEST = $HTTP_GET_VARS;
	break;
	case "POST":
		$REQUEST = $HTTP_POST_VARS;
	break;
	default:
	showStatus(false);
}

if (isset($REQUEST["action"]))
{
	$action = $REQUEST["action"];
}

if (is_callable("action_" . $action, false)) 
{
	$action = "action_" . $action;
	$status = $action();
	if (!$status) {
		exitError();
	}
	showStatus($status);
} else {
	showStatus(false);
}

exit;

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

function showStatus($status)
{
	echo (($status) ? "OK" : "ERR" ) . "\n";
}

function exitError($error=null)
{
	showStatus(false);

	echo "0\n";
	echo "0\n";
	echo $error . "\n";

	exit;
}

function exitOK($result)
{
    $result = base64_encode(serialize($result));
	showStatus(true);

	echo strlen($result) . "\n";
	echo md5($result) . "\n";
	echo $result . "\n";

	exit;
}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

function action_echo()
{
    global $REQUEST;

    if (isset($REQUEST["timestamp"]))
    {   
        $timestamp = $REQUEST["timestamp"];
    } else {
        return false;
    }

    exitOK($timestamp);
}

function action_encode()
{
	global $REQUEST;
	global $errStatus;

    if (isset($REQUEST["options"]))
    {
    	$options = $REQUEST["options"];
    } else {
    	return false;
    }

    if (isset($REQUEST["source_data"])) {
        $source_data = unserialize(base64_decode($REQUEST["source_data"]));
        if ($source_data === false || gettype($source_data) != "string") {
    		return false;
        }
    } else {
        return false;
    }

    if (isset($REQUEST["preamble_data"])) {
        $preamble_data = unserialize(base64_decode($REQUEST["preamble_data"]));
        if ($preamble_data === false || gettype($preamble_data) != "string") {
    		return false;
        }
    }

	$tempDir = ini_get("upload_tmp_dir");
	if (!(is_dir($tempDir) && is_writable($tempDir) && file_exists($tempDir))) {
        return false;
	}

	$tempSrcName = tempnam($tempDir, "src_");
	$tempSrcNamePhp = $tempSrcName . ".php";
	$tempDstName = tempnam($tempDir, "dst_");
	$tempDstNamePhp = $tempDstName . ".php";
    if (isset($REQUEST["preamble_data"])) {
		$tempPreambleName = tempnam($tempDir, "preamble_");
		$tempPreambleNamePhp = $tempDstName . ".php";
	}

	$handle = @fopen($tempSrcNamePhp, "wb");
	if ($handle) {
		fwrite($handle, $source_data);
		fclose($handle);
	} else {
		unlink($tempSrcName);
		unlink($tempSrcNamePhp);
    	if (isset($REQUEST["preamble_data"])) {
			unlink($tempPreambleName);
			unlink($tempPreambleNamePhp);
    	}
        return false;
	}

	if (isset($REQUEST["preamble_data"])) {
    	$handle = @fopen($tempPreambleNamePhp, "wb");
    	if ($handle) {
    		fwrite($handle, $preamble_data);
    		fclose($handle);
    	} else {
    		unlink($tempSrcName);
    		unlink($tempSrcNamePhp);
			unlink($tempPreambleName);
    		unlink($tempPreambleNamePhp);
            return false;
    	}
	}

	$systemCommand = IONCUBE_ENCODER . " " . $options;
	if (isset($REQUEST["preamble_data"])) {
		$systemCommand .= " --preamble-file " . $tempPreambleNamePhp;
	}
	$systemCommand .= " " . $tempSrcNamePhp . " -o " . $tempDstNamePhp;

	system($systemCommand);

    $handle = @fopen($tempDstNamePhp, "rb");
	if ($handle) {
    	$contents = fread($handle, filesize($tempDstNamePhp));
    	fclose($handle);
	} else {
		unlink($tempSrcName);
		unlink($tempSrcNamePhp);
		unlink($tempDstName);
		unlink($tempDstNamePhp);
    	if (isset($REQUEST["preamble_data"])) {
			unlink($tempPreambleName);
			unlink($tempPreambleNamePhp);
    	}
        return false;
	}

	unlink($tempSrcName);
	unlink($tempSrcNamePhp);
	unlink($tempDstName);
	unlink($tempDstNamePhp);
	if (isset($REQUEST["preamble_data"])) {
		unlink($tempPreambleName);
		unlink($tempPreambleNamePhp);
	}

    $result = array
    (
    	"encoded_data" 		=> $contents,
    	"encode_command" 	=> $systemCommand,
    );

    exitOK($result);
}

?>