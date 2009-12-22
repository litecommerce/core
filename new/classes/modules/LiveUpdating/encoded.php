<?php

define("LUS_SERVICE_URL", "http://liveupdate.litecommerce.com/service.php");

function LiveUpdating_get_servise_url($_this)
{
	if ($_this->session->get("service_url")) {
		return $_this->session->get("service_url");
	} else {
		return LUS_SERVICE_URL;
	}
}

function LiveUpdating_liveupdate_dialog_init($_this)
{
	if (isset($_REQUEST)) {
		if ($_REQUEST["action"] == "set_test_mode") {
			if ($_REQUEST["code"] == "46e910e31c") {   // first 10 letters from: md5 -s lclu_test_mode
				$_this->session->set("lu_test_mode", true);
			} else {
				$_this->session->set("lu_test_mode", null);
			}
		}

		if ($_REQUEST["action"] == "set_service_url") {
			$service_url = trim($_REQUEST["service_url"]);
			if (strlen($service_url) > 0) {
				$_this->session->set("service_url", $service_url);
			} else {
				$_this->session->set("service_url", null);
			}
		}
	}
}

function LiveUpdating_text_decryption($text)
{
    $len = strlen($text); 
    $result = "";
    for ($i=0; $i<$len; $i+=2) { 
    	$result .= chr(hexdec(substr($text, $i, 2))); 
    }
	return base64_decode($result);
}

function LiveUpdating_text_encryption($text)
{
	return bin2hex(base64_encode($text));
}

function LiveUpdating_get_version(&$_this)
{
    $version = $_this->xlite->config->get("Version.version");
	$version = str_replace(" build ", ".", $version);

	return $version;
}

function LiveUpdating_getModulesUpdateInfo(&$_this)
{
	$mlist = $_this->get("config.LiveUpdating.modules_last_update");
	if (!is_array($mlist))
		$mlist = array();

	$modules = $_this->xlite->mm->get("modules");
	foreach ($modules as $module) {
		$name = $module->get("name");
		$mlist[$name]["version"] = $module->get("version");
		if (!array_key_exists($name, $mlist)) {
			$mlist[$name]["last_update"] = "";
		}
	}

	$config =& func_new("Config");
	$config->createOption("LiveUpdating", "modules_last_update", serialize($mlist), "serialized");

	return $mlist;
}

function LiveUpdating_getUpdatesNumber(&$_this)
{
	$lastChecked = $_this->xlite->config->get("LiveUpdating.last_checked");
	if (isset($lastChecked) && is_array($lastChecked)) {
		if (isset($lastChecked["next_check"]) && (time() < $lastChecked["next_check"]) && isset($lastChecked["updates_number"])) {
        	$_this->_updatesNumber = $lastChecked["updates_number"];
        	$_this->xlite->set("LU_updatesNumber", $_this->_updatesNumber);
        	return $_this->_updatesNumber;
		}
	}

	$modules = LiveUpdating_getModulesUpdateInfo($_this);

    require_once "PEAR.php";
    require_once "HTTP/Request.php";

	$liveupdateURL = LiveUpdating_get_servise_url($_this);
    $http = new HTTP_Request($liveupdateURL); 
    $http->_timeout = 10;
    $http->_method = HTTP_REQUEST_METHOD_POST;
    $http->addPostData("action", "updates_number");
    $http->addPostData("version", LiveUpdating_get_version($_this));
    $http->addPostData("last_update", $_this->getLastUpdate());
	$http->addPostData("modules", base64_encode(serialize($modules)));

	if ($_this->session->get("lu_test_mode")) {
		$http->addPostData("test_mode", 1);
	}

    $result = @$http->sendRequest();
    if (PEAR::isError($result)) {
    	$_this->errMessage = "Connection error.";
    	$_this->_updatesNumber = 0;
    	return $_this->_updatesNumber;
    }
    $result = $http->getResponseBody();

    $result = explode("\n", $result);
    if (!(is_array($result) && count($result) >= 4)) {
    	$_this->errMessage = "Bad data.";
    	$_this->_updatesNumber = 0;
    	return $_this->_updatesNumber;
    }

    list($status, $length, $crc, $data) = $result;

    if ($status != "OK") {
    	$_this->errMessage = "Server error.";
    	$_this->_updatesNumber = 0;
    	return $_this->_updatesNumber;
    }
    if (!(strlen($data) == $length && md5($data) == $crc)) {
    	$_this->errMessage = "Corrupted data.";
    	$_this->_updatesNumber = 0;
    	return $_this->_updatesNumber;
    }

    $data = unserialize(base64_decode($data));
	$_this->_updatesNumber = $data["updates_number"];
	$_this->xlite->set("LU_updatesNumber", $_this->_updatesNumber);

	if (!(isset($lastChecked) && is_array($lastChecked))) {
		$lastChecked = array();
	}
	
	$currTime = time();
	$lastChecked["next_check"] = mktime(0, 0, 0, date("m", $currTime), date("d", $currTime) + 1, date("Y", $currTime));
	$lastChecked["updates_number"] = $_this->_updatesNumber;
	$config =& func_new("Config");
	$config->createOption("LiveUpdating", "last_checked", serialize($lastChecked), "serialized");

	return $_this->_updatesNumber;
}

function LiveUpdating_getUpdates(&$_this)
{
	$updatesNumber = $_this->getUpdatesNumber();

	$lastChecked = $_this->xlite->config->get("LiveUpdating.last_checked");
	if (isset($lastChecked) && is_array($lastChecked)) {
		if (isset($lastChecked["next_check"]) && (time() < $lastChecked["next_check"]) && isset($lastChecked["updates_announcement"])) {
        	$_this->_updatesNumber = $lastChecked["updates_number"];
        	$_this->_updates = $lastChecked["updates_announcement"];
        	return $_this->_updates;
		}
	}

	$modules = LiveUpdating_getModulesUpdateInfo($_this);

    require_once "PEAR.php";
    require_once "HTTP/Request.php";

	$liveupdateURL = LiveUpdating_get_servise_url($_this);
    $http = new HTTP_Request($liveupdateURL); 
    $http->_timeout = $updatesNumber * 10;
    $http->_method = HTTP_REQUEST_METHOD_POST;
    $http->addPostData("action", "get_updates_announcement");
    $http->addPostData("version", LiveUpdating_get_version($_this));
    $http->addPostData("last_update", $_this->getLastUpdate());
	$http->addPostData("modules", base64_encode(serialize($modules)));

	if ($_this->session->get("lu_test_mode")) {
		$http->addPostData("test_mode", 1);
	}

    $result = @$http->sendRequest();
    if (PEAR::isError($result)) {
    	$_this->errMessage = "Connection error.";
    	$_this->_updates = null;
    	return $_this->_updates;
    }
    $result = $http->getResponseBody();

    $result = explode("\n", $result);
    if (!(is_array($result) && count($result) >= 4)) {
    	if (is_array($result)) {
            if (isset($result[0]) && $result[0] == "ERR") {
            	$_this->errMessage = "Server  error.";
            	$_this->_updates = null;
            	return $_this->_updates;
            }
    	}
    	$_this->errMessage = "Bad data.";
    	$_this->_updates = null;
    	return $_this->_updates;
    }

    list($status, $length, $crc, $data) = $result;

    if ($status != "OK") {
    	$_this->errMessage = "Server   error.";
    	$_this->_updates = null;
    	return $_this->_updates;
    }
    if (!(strlen($data) == $length && md5($data) == $crc)) {
    	$_this->errMessage = "Corrupted data.";
    	$_this->_updates = null;
    	return $_this->_updates;
    }

    $data = unserialize(base64_decode($data));

	$_this->_updatesNumber = $data["updates_number"];
	$_this->_updates = $data["updates"];

	if (!(isset($lastChecked) && is_array($lastChecked))) {
		$lastChecked = array();
	}
	
	$currTime = time();
	$lastChecked["next_check"] = mktime(0, 0, 0, date("m", $currTime), date("d", $currTime) + 1, date("Y", $currTime));
	$lastChecked["updates_number"] = $_this->_updatesNumber;
	$lastChecked["updates_announcement"] = $_this->_updates;
	$config =& func_new("Config");
	$config->createOption("LiveUpdating", "last_checked", serialize($lastChecked), "serialized");

	return $_this->_updates;
}

function LiveUpdating_getUpdateData(&$_this, $update_id, $update_item_id=0)
{
	$_this->_updates = null;

	$updatesNumber = 0;
	if ($update_item_id == 0) {
		$updatesNumber = $_this->getUpdatesNumber();

		if ($updatesNumber == 0) {
    		return $_this->_updates;
		}
	} else {
		$updatesNumber = 1;
	}

    require_once "PEAR.php";
    require_once "HTTP/Request.php";

	$liveupdateURL = LiveUpdating_get_servise_url($_this);
    $http = new HTTP_Request($liveupdateURL); 
    $http->_timeout = $updatesNumber * 10;
    $http->_method = HTTP_REQUEST_METHOD_POST;
    $http->addPostData("action", "get_updates");
    $http->addPostData("version", LiveUpdating_get_version($_this));
    $http->addPostData("updates[0]", $update_id);
	$http->addPostData("update_item_id", $update_item_id);

	if ($_this->session->get("lu_test_mode")) {
		$http->addPostData("test_mode", 1);
	}

    $result = @$http->sendRequest();
    if (PEAR::isError($result)) {
    	$_this->errMessage = "Connection error.";
    	return null;
    }
    $result = $http->getResponseBody();

    $result = explode("\n", $result);
    if (!(is_array($result) && count($result) >= 4)) {
    	$_this->errMessage = "Bad data.";
    	return null;
    }

    list($status, $length, $crc, $data) = $result;

    if ($status != "OK") {
    	$_this->errMessage = "Server    error.";
    	return null;
    }
    if (!(strlen($data) == $length && md5($data) == $crc)) {
    	$_this->errMessage = "Corrupted data.";
    	return null;
    }

	$lastChecked = $_this->xlite->config->get("LiveUpdating.last_checked");
	if (!(isset($lastChecked) && is_array($lastChecked))) {
		$lastChecked = array();
	}

	$return_data = unserialize(base64_decode($data));

	if ($update_item_id > 0) {
		return $return_data;
	}

	// store last_update for modules
	foreach ($return_data as $info) {
		if (preg_match("/^M:(\w+):([\w\.]+)/", $info["version"], $out)) {
			$mlist = $_this->get("config.LiveUpdating.modules_last_update");
			$mlist[$out[1]]["last_update"] = $info["update_id"];

			$config =& func_new("Config");
			$config->createOption("LiveUpdating", "modules_last_update", serialize($mlist), "serialized");
		}
	}

	$currTime = time();
	$lastChecked["next_check"] = mktime(0, 0, 0, date("m", $currTime), date("d", $currTime) + 1, date("Y", $currTime));
	if (isset($lastChecked["updates_number"])) {
		unset($lastChecked["updates_number"]);
	}
	if (isset($lastChecked["updates_announcement"])) {
		unset($lastChecked["updates_announcement"]);
	}
	$config =& func_new("Config");
	$config->createOption("LiveUpdating", "last_checked", serialize($lastChecked), "serialized");

	return $return_data;
}

?>
