<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

define('LC_UPLOAD_OK',        0); // OK
define('LC_UPLOAD_NO_FILE',   1); // is not uploaded file
define('LC_UPLOAD_TMPDIR_NO', 2); // upload_tmp_dir not exists
define('LC_UPLOAD_TMPDIR_RO', 3); // upload_tmp_dir is not writable
define('LC_UPLOAD_TARGET_NO', 4); // target dir not exists
define('LC_UPLOAD_TARGET_RO', 5); // target dir is not writable
define('LC_UPLOAD_MAX_SIZE',  6); // max size error
define('LC_UPLOAD_UNK',       7); // unknown error

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Upload extends XLite_Base
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
    public $code;	
    public $_dir;	

    public $name;	
    public $type;	
    public $size;	
    public $tmp_name;	
    public $error;

    public function __construct($uploadArray = array())
    {
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
                return "The \"" . ini_get('upload_tmp_dir') . "\" directory does not exist, that is why it is impossible to upload files to your server. This is due to incorrect web server configuration, please contact your hosting provider in order to correct the settings.";
            break;
            case LC_UPLOAD_TMPDIR_RO:
                return "The \"" . ini_get('upload_tmp_dir') . "\" directory is not writable, that is why it is impossible to upload files to your server. This is due to incorrect web server configuration, please contact your hosting provider in order to correct the settings.";
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
