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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */


/**
* GnuPG wrapper.
*
* @package Module_AdvancedSecurity
* @version $Id$
*/
class XLite_Module_AdvancedSecurity_Model_GPG extends XLite_Base
{	
    public $tmpdir    = null;	
    public $homedir   = null;	
    public $exe       = null;	
    public $recipient = null;

	protected $_publicKey = null;
	protected $_publicKeyInfo = null;
	protected $_secretKey = null;
    protected $_secretKeyInfo = null;

    public function __construct() // {{{
    {
        parent::__construct();
        
        //
        // GnuPG binary
        //

        // use config value
        $exe = $this->get("config.AdvancedSecurity.gpg_binary_path");
        if (!is_null($exe) && @file_exists($exe)) {
            $this->exe = $exe;
        }
        // attempt to find executable
        else {
            $this->exe = func_find_executable("gpg");
        }
        if (!$this->exe) {
            $this->executable = false;
        } else {
            $this->executable = function_exists("is_executable") ? @is_executable($this->exe) : true;
            $this->exe = $this->validatePath($this->exe);
            if (!$this->exe) $this->executable = false;
        }

        //
        // GnuPG home directory
        //

	// use config value
	$cfg_home = $this->get("config.AdvancedSecurity.gpg_home");
	$home = realpath($cfg_home);
	if (!is_null($cfg_home) && $cfg_home != "" && @is_dir($home)) {
	    $home = $this->validatePath($home, true);
        $this->homedir = $home;
    }
    // look for envuronment variable
    elseif (isset($_ENV["GNUPGHOME"]) && @file_exists($_ENV["GNUPGHOME"])) {
        $this->homedir = $_ENV["GNUPGHOME"];
    }
        $this->writable = stristr(PHP_OS, "win") ? true : @is_writable($home);

        //
        // GnuPG user ID
        //
        $this->recipient = $this->get("config.AdvancedSecurity.gpg_user_id");

        //
        // Temporary directory
        // 

		$upload_tmp_dir = ini_get("upload_tmp_dir");
        if (isset($_ENV['TMPDIR'])) {
            $this->tmpdir = $_ENV['TMPDIR'];
        } elseif (is_dir('/tmp')) { 
            $this->tmpdir = '/tmp';
        } elseif (isset($_ENV['TMP'])) {
            $this->tmpdir = $_ENV['TMP'];
        } elseif (isset($_ENV['TEMP'])) { 
            $this->tmpdir = $_ENV['TEMP'];
        } elseif (!empty($upload_tmp_dir) && is_dir($upload_tmp_dir)) {
			$this->tmpdir = $upload_tmp_dir;
        }    
    } // }}}

    function cleanup() // {{{
    {
        // remove GnuPG keyring files
        @unlink($this->homedir . "/pubring.gpg~");
        @unlink($this->homedir . "/secring.gpg~");
        @unlink($this->homedir . "/trustdb.gpg~");
        return;
    } // }}}
    
	function validatePath($cmd_file, $isFolder = false)
	{
		// lookup cache for adjusted name
		$cfg = new XLite_Model_Config();
		if ($cfg->find("category='AdvancedSecurity' AND name='executable_cache'")) {
			$valid_names = unserialize($cfg->get("value"));
		}
		if (!is_array($valid_names)) $valid_names = array();

		$key = $cmd_file;
		if (isset($valid_names[$key]) && strlen($valid_names[$key]) > 0) {
			return $valid_names[$key];
		}

		if (substr(PHP_OS, 0, 3) != 'WIN') {
			// adjust UNIX-like file names:
			$cmd_file = preg_replace("/[ ]/", '&slash;\0', $cmd_file);
			$cmd_file = preg_replace("/&slash;/", '\\', $cmd_file);
		} elseif (class_exists("COM")) {
			// adjust Windows long file names using COM:
			// create FSO instance
			$exFSO = new COM("Scripting.FileSystemObject");
			if (!is_object($exFSO)) {
				// Error: Could not create Scripting.FileSystemObject
				return false;
			}
			// get file object
			$exFile = $isFolder ? $exFSO->GetFolder($cmd_file) : $exFSO->GetFile($cmd_file);
			$cmd_file = (!empty($exFile->Path))?$exFile->Path:false;
		}

		if ($cmd_file) {
			$valid_names[$key] = $cmd_file;
			$cfg->createOption("AdvancedSecurity", "executable_cache", serialize($valid_names), "serialized");
		}
		return $cmd_file;
	}

	function execGPG($cmd, $input = "")
	{
		$log = tempnam($this->tmpdir, "log");
		$cmd .= " 2>$log";
		$mode = (strlen($input))?"w":"r";

		$fp = @popen($cmd, $mode);
		if (!$fp) {
			$this->errorLog = "Module AdvancedSecurity: unable to encrypt data: cannot popen $cmd";
			return false;
		}
		if ($mode == "w") {
			$written = @fwrite($fp, $input, strlen($input));
		}
		if ($mode == "r") {
			$data = @fread($fp, 65535);
		}
		pclose($fp);

		$this->errorLog = @file_get_contents($log);
		if ($this->errorLog === false) {
			$this->errorLog = "Module AdvancedSecurity: cannot scan the execution log: $log";
		}
		@unlink($log);
		if (!empty($this->errorLog)) {
			$this->logger->log($this->errorLog);
		}

		if ($mode == "r") return $data;
		return true;
	}

	function file_put_contents($file, $contents)
	{
		// save content to temporary file
		$fd = @fopen($file, "wb");
		if (!$fd) return false;
		$written = @fwrite($fd, $contents, strlen($contents));
		fclose($fd);
		return $written;
	}

	function encrypt($content) // {{{
	{
		// file to encrypt data to
		$dst = tempnam($this->tmpdir, "dst");
		$cmd = $this->exe . " --yes --no-tty --batch --disable-mdc --no-random-seed-file --no-verbose --no-greeting --armor --no-secmem-warning --no-permission-warning --no-options --quiet --no-random-seed-file --homedir $this->homedir --encrypt --always-trust --recipient \"$this->recipient\" --output $dst";
		// encrypt
		$result = null;
		if ($this->execGPG($cmd, $content)) {
			// get result && cleanup
			$result = file_get_contents($dst);
			if (!empty($result)) {
				$this->errorLog = "";
			}
		}
		@unlink($dst);
		return $result;
	} // }}}

	function decrypt($content, $password = null) // {{{
	{
		$passphrase = is_null($password) ? $this->session->get("masterPassword") : $password;
		$src = tempnam($this->homedir, "src"); // encrypted data
		$dst = tempnam($this->homedir, "dst"); // decrypted data
		$cmd = $this->exe . " --yes --no-tty --batch --disable-mdc --no-random-seed-file --no-verbose --no-greeting --armor --no-secmem-warning --no-permission-warning --no-options --quiet --passphrase-fd 0 --no-random-seed-file --homedir $this->homedir --recipient \"$this->recipient\" --decrypt --output $dst $src";

		// save content to temporary file
		if (!$this->file_put_contents($src, $content)) {
			$this->errorLog = "Module AdvancedSecurity: can't open temporary file $src for writing";
			return null;
		}

		// decrypt
		$result = null;
		if ($this->execGPG($cmd, $passphrase)) {
			// get result && cleanup
			$result = file_get_contents($dst);
			if (!empty($result)) {
				$this->errorLog = "";
			}
		}
		@unlink($src);
		@unlink($dst);
		return $result;
	} // }}}

    function isEncoded($data) // {{{
    {
        return is_scalar($data) && func_starts_with($data, "-----BEGIN PGP MESSAGE-----");
    } // }}}

    function deleteKeys() // {{{
    {
        // remove GnuPG keyring files
        @unlink($this->homedir . "/pubring.gpg");
        @unlink($this->homedir . "/secring.gpg");
        @unlink($this->homedir . "/trustdb.gpg");
        $this->cleanup();
    } // }}}

	function uploadKeys() // {{{
	{
		// import keys to keyring
		if (is_uploaded_file($_FILES["gpg_public_file"]["tmp_name"]) && is_uploaded_file($_FILES["gpg_secret_file"]["tmp_name"])) {
			$cmd = $this->exe . " --yes --no-tty --batch --disable-mdc --no-random-seed-file --no-verbose --no-greeting --no-secmem-warning --no-permission-warning --no-options --quiet --no-random-seed-file --homedir " . $this->homedir . " --import " . $_FILES["gpg_public_file"]["tmp_name"] . " " . $_FILES["gpg_secret_file"]["tmp_name"];
			$this->execGPG($cmd);
			@unlink($_FILES["gpg_public_file"]["tmp_name"]);
			@unlink($_FILES["gpg_secret_file"]["tmp_name"]);
			$this->cleanup();
			return $this->getPublicKey() && $this->getSecretKey();
		}
		return false;
	} // }}}

    function deleteKey($key) // {{{
    {
        $file = $this->homedir . '/' . $key . '.gpg';
        @unlink($file);
        $this->cleanup();
    } // }}}

    function getPublicKey() // {{{
    {
        if (is_null($this->_publicKey)) {
            $this->_publicKey = $this->_getKeyData("public");
        }
        return $this->_publicKey;
    } // }}}

    function getSecretKey() // {{{
    {
        if (is_null($this->_secretKey)) {
            $this->_secretKey = $this->_getKeyData("secret");
        }
        return $this->_secretKey;
    } // }}}

    function _getKeyData($key = "public") // {{{
    {
        if ($key == "public") {
            $target = " --export ";
        } elseif ($key == "secret") {
            $target = " --export-secret-keys ";
        } else {
            return "";
        }    
		$cmd = $this->exe . " --armor --yes --no-tty --disable-mdc --no-random-seed-file --no-verbose --no-greeting --no-secmem-warning --no-permission-warning --no-options --no-random-seed-file --homedir " . $this->homedir . $target . "\"" . $this->get("config.AdvancedSecurity.gpg_user_id") . "\"";
		$data = $this->execGPG($cmd);
        return $data;
    } // }}}

    function getPublicKeyInfo() // {{{
    {
        if (is_null($this->_publicKeyInfo)) {
            $this->_publicKeyInfo = $this->_getKeyInfo("public");
        }
        return $this->_publicKeyInfo;
    } // }}}

    function getSecretKeyInfo() // {{{
    {
        if (is_null($this->_secretKeyInfo)) {
            $this->_secretKeyInfo = $this->_getKeyInfo("secret");
        }
        return $this->_secretKeyInfo;
    } // }}}

    function _getKeyInfo($key = "public") // {{{
    {
        if ($key == "public") {
            $target = " --list-keys";
        } elseif ($key == "secret") {
            $target = " --list-secret-keys ";
        } else {
            return "";
        }    
        $cmd = $this->exe . " --armor --yes --no-tty --disable-mdc --no-random-seed-file --no-verbose --no-greeting --no-secmem-warning --no-permission-warning --no-options --no-random-seed-file --homedir " . $this->homedir . $target;
		$data = $this->execGPG($cmd);
        return $data;
    } // }}}

    function isKeyValid($key, $type) // {{{
    {
        $type = strtoupper($type);
        return strlen($key) && strpos($key, "-----BEGIN PGP $type KEY BLOCK-----") !== false;
    } // }}}

    function isPasswordValid($pass = null) // {{{
    {
        $password = is_null($pass) ? $this->get("session.masterPassword") : $pass;
        return $this->decrypt($this->encrypt("test"), $password) == "test";
    } // }}}

	function isConfigurationValid()
	{
		if (!($this->get("homedir") && $this->get("writable"))) return false;
		if (!($this->get("exe") && $this->get("executable"))) return false;
		if (!$this->get("recipient")) return false;
		if (!$this->get("publicKey")) return false;
		if (!$this->get("secretKey")) return false;
		if (!$this->encrypt("Test text for encryption.")) return false;
		return true;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
