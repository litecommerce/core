<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Admin_Dialog_module_LiveUpdating description.
*
* @package Module_LiveUpdating
* @access public
* @version $Id: module.php,v 1.6 2008/10/28 07:27:30 vgv Exp $
*/
class Admin_Dialog_module_LiveUpdating extends Admin_Dialog_module
{
	var $_checkFTP = null;

	function init()
	{
		parent::init();

		if ($this->page == "LiveUpdating") {
        	$lay =& func_get_instance("Layout");
        	$lay->addLayout("general_settings.tpl", "modules/LiveUpdating/config.tpl");
        }
	}

	function handleRequest()
	{
	    if (isset($_REQUEST["ftp_password"])) {
	    	$password = $this->text_encryption($_REQUEST["ftp_password"]);
	    	$_REQUEST["ftp_password"] = $password;
	    	$_POST["ftp_password"] = $password;
	    }

		if (isset($_REQUEST["ftp_port"])) {
			$port = $_REQUEST["ftp_port"];
			if (!is_numeric($port) || $port < 0) {
				$_REQUEST["ftp_port"] = $_POST["ftp_port"] = 21; // set to default FTP port
			}
		}

		parent::handleRequest();
	}

	function getOptionSize($name)
	{
		switch($name)
		{
			case "ftp_host":
				$size = 30;
			break;
			case "ftp_dir":
				$size = 40;
			break;
			default:
				$size = 10;
			break;
		}

		return $size;
	}

	function checkFTP()
	{
		if (isset($this->_checkFTP)) {
			return $this->_checkFTP;
		}

		$this->_checkFTP = 0;

		if (!function_exists("ftp_connect")) {
			$this->_checkFTP = 100;
			return $this->_checkFTP;
		}

		if ($this->config->get("LiveUpdating.ftp_host") == "" || $this->config->get("LiveUpdating.ftp_login") == "") {
			// return 'FTP connection failed' if hostname or login not set
			$this->_checkFTP = 1;
			return $this->_checkFTP;
		}

		$conn_id = @ftp_connect($this->config->get("LiveUpdating.ftp_host"), $this->config->get("LiveUpdating.ftp_port")); 
		if ($conn_id === false) {
			$this->_checkFTP = 1;
			return $this->_checkFTP;
		}

		@ftp_pasv($conn_id, $this->config->get("LiveUpdating.ftp_passive"));

		if (!@ftp_login($conn_id, $this->config->get("LiveUpdating.ftp_login"), $this->getFtpPassword())) {
			@ftp_close($conn_id);
			$this->_checkFTP = 2;
			return $this->_checkFTP;
		}

		if (!@ftp_chdir($conn_id, $this->config->get("LiveUpdating.ftp_dir"))) {
			@ftp_close($conn_id);
			$this->_checkFTP = 3;
			return $this->_checkFTP;
		}

		if (!@ftp_put($conn_id, "ftp_check.txt", "./classes/modules/LiveUpdating/ftp_check.txt", FTP_BINARY)) {
			@ftp_close($conn_id);
			$this->_checkFTP = 4;
			return $this->_checkFTP;
		}

        if (!LC_OS_IS_WIN) {
            // don't check "chmod" on Windows hosting
            if (!function_exists("ftp_chmod")) {
                $chmod_cmd="CHMOD 0666 ftp_check.txt";
                if (!@ftp_site($conn_id, $chmod_cmd)) {
                    $this->_checkFTP = 5;
                    return $this->_checkFTP;
                }
            } else {
                if (!@ftp_chmod($conn_id, 0666, "ftp_check.txt")) {
                    @ftp_close($conn_id);
                    $this->_checkFTP = 5;
                    return $this->_checkFTP;
                }
            }
        }

		if (!@ftp_delete($conn_id, "ftp_check.txt")) {
			@ftp_close($conn_id);
			$this->_checkFTP = 6;
			return $this->_checkFTP;
		}

		@ftp_close($conn_id);
		return $this->_checkFTP;
	}

	function text_encryption($text)
	{
        require_once "modules/LiveUpdating/encoded.php";
		return LiveUpdating_text_encryption($text);
	}

	function text_decryption($text)
	{
        require_once "modules/LiveUpdating/encoded.php";
		return LiveUpdating_text_decryption($text);
	}

	function getFtpPassword()
	{
		return $this->text_decryption($this->config->get("LiveUpdating.ftp_password"));
	}
}

?>
