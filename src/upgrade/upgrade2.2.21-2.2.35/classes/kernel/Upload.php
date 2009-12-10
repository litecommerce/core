<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2007 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL:                                                        |
| http://www.litecommerce.com/software_license_agreement.html                  |
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
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/
/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

// for PHP 4.2.* {{{
if (!defined('UPLOAD_ERR_OK')) {
    define('UPLOAD_ERR_OK',        0);    
    define('UPLOAD_ERR_INI_SIZE',  1);
    define('UPLOAD_ERR_FORM_SIZE', 2);
    define('UPLOAD_ERR_PARTIAL',   3);
    define('UPLOAD_ERR_NO_FILE',   4);
}
// }}}

define('LC_UPLOAD_OK',        0); // OK
define('LC_UPLOAD_NO_FILE',   1); // is not uploaded file
define('LC_UPLOAD_TMPDIR_NO', 2); // upload_tmp_dir not exists
define('LC_UPLOAD_TMPDIR_RO', 3); // upload_tmp_dir is not writable
define('LC_UPLOAD_TARGET_NO', 4); // target dir not exists
define('LC_UPLOAD_TARGET_RO', 5); // target dir is not writable
define('LC_UPLOAD_MAX_SIZE',  6); // max size error
define('LC_UPLOAD_UNK',       7); // unknown error

/**
* Class Upload implements the mechanism for files uploading.
*
* @package Kernel
* @access public
* @version $Id: Upload.php,v 1.3 2007/05/21 11:53:28 osipov Exp $
*/
class Upload extends Object
{
    /**
    * Upload code.
    * Values:
    *   LC_UPLOAD_OK        - OK
    *   LC_UPLOAD_NO_FILE   - is not uploaded file (error)
    *   LC_UPLOAD_TMPDIR_NO - upload_tmp_dir not exists (error)
    *   LC_UPLOAD_TMPDIR_RO - upload_tmp_dir is not writable (error)
    *   LC_UPLOAD_TARGET_NO - target dir not exists (error)
    *   LC_UPLOAD_TARGET_RO - target dir is not writable (error)
    *   LC_UPLOAD_MAX_SIZE  - max size error (error)
    *   LC_UPLOAD_UNK       - unknown error (error)
    */
    var $code;
    var $_dir;

    var $name;
    var $type;
    var $size;
    var $tmp_name;
    var $error;

    function constructor($uploadArray = array())
    {
        parent::constructor();
      
        $this->name     = (isset($uploadArray['name']) ? $uploadArray['name'] : null);
        $this->type     = (isset($uploadArray['type']) ? $uploadArray['type'] : null);
        $this->size     = (isset($uploadArray['size']) ? $uploadArray['size'] : null);
        $this->tmp_name = (isset($uploadArray['tmp_name']) ? $uploadArray['tmp_name'] : null);
        $this->error    = (isset($uploadArray['error']) ? $uploadArray['error'] : null);
    }

    /**
    * Move uploaded file.
    * @param string $name target file
    * @return true - OK, false - error occured.
    */
    function move($file)
    {
        if($this->code === LC_UPLOAD_NO_FILE) {
            return false;
		}
        $this->code = LC_UPLOAD_OK;
        $this->_dir = dirname($file);
        $move       = @move_uploaded_file($this->tmp_name, $file);
        if (!$move) {
            if (!is_writable($this->_dir)) {
                $this->code = (!file_exists($this->_dir) ? LC_UPLOAD_TARGET_NO : LC_UPLOAD_TARGET_RO);
            } elseif (ini_get('upload_tmp_dir') && !is_writable(ini_get('upload_tmp_dir'))) {
                $this->code = (!file_exists(ini_get('upload_tmp_dir')) ? LC_UPLOAD_TMPDIR_NO : LC_UPLOAD_TMPDIR_RO);
            } elseif (!is_null($this->error)) {
                switch($this->error) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $this->code = LC_UPLOAD_MAX_SIZE;
                    break;
                    case UPLOAD_ERR_PARTIAL:
                        $this->code = LC_UPLOAD_UNK;
                    break;
                    case UPLOAD_ERR_NO_FILE:
                        $this->code = LC_UPLOAD_NO_FILE;
                    break;
                }
            } elseif (!is_uploaded_file($this->tmp_name)) {
                $this->code = LC_UPLOAD_NO_FILE;
            } else {
                $this->code = LC_UPLOAD_UNK;
            }
        }
        
        return $move;
    }

    function isUpload()
    {
        return is_uploaded_file($this->tmp_name);
    }

    /**
    * Return code
    */
    function getCode()
    {
        return $this->code;
    }

    /**
    * Return name
    */
    function getName()
    {
        return $this->name;
    }

    /**
    * Return tmp name
    */
    function getTmpName()
    {
        return $this->tmp_name;
    }

    /**
    * Return size
    */
    function getSize()
    {
        return $this->size;
    }

    /**
    * Return type
    */
    function getType()
    {
        return $this->type;
    }

    /**
    * Return upload error message by code
    */
    function getErrorMessage()
    {
        switch ($this->code) {
            case LC_UPLOAD_OK:
                return "";
            break;
            case LC_UPLOAD_NO_FILE:
                return "The file has not been uploaded.";
            break;
            case LC_UPLOAD_TMPDIR_NO:
                return "The \"" . ini_get("upload_tmp_dir") . "\" directory does not exist, that is why it is impossible to upload files to your server. This is due to incorrect web server configuration, please contact your hosting provider in order to correct the settings.";
            break;
            case LC_UPLOAD_TMPDIR_RO:
                return "The \"" . ini_get("upload_tmp_dir") . "\" directory is not writable, that is why it is impossible to upload files to your server. This is due to incorrect web server configuration, please contact your hosting provider in order to correct the settings.";
            break;
            case LC_UPLOAD_TARGET_NO:
                return "The \"" . $this->_dir . "\" directory does not exist.";
            break;
            case LC_UPLOAD_TARGET_RO:
                return "The \"" . $this->_dir . "\" directory is not writable.";
            break;
            case LC_UPLOAD_MAX_SIZE:
                return "The file is too big and cannot be uploaded.";
            break;
            default:
                return "An unknown error was encountered during the file upload.";
            break;
        }
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
