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

@set_time_limit(0);

/**
* @package Dialog
* @access public
* @version $Id$
*
*/
class XLite_Controller_Admin_Files extends XLite_Controller_Admin_Abstract
{	
    public $count = 0; //total files count;	
    public $action = "default";
    
	// FIXME - check this function 
    function handleRequest()
    {
        require_once LC_ROOT_DIR . 'lib' . LC_DS . 'Archive' . LC_DS . 'Tar.php';

        parent::handleRequest();

        die();
    }
    
    function action_default()
    {
        print "<pre>";
        $this->get_files('.');
        print $this->count;
    }

    function action_tar()
    {
        $tar = new Archive_Tar('STDOUT');
        $this->startDownload("backup.tar");
        $files = array();
        if ($handle = opendir('.')) { 
            while (false !== ($file = readdir($handle))) { 
                if ($file{0} != "." && $file != "var") {
                    $files[] = $file;
                }
            }
        }    
		if (isset(XLite_Core_Request::getInstance()->mode) && XLite_Core_Request::getInstance()->mode == "full") {
			// backup database as well
			$this->db->backup(SQL_DUMP_FILE, false);
			$files[] = SQL_DUMP_FILE;
		}
        $tar->create($files);
        $tar->_close();
    }

    function action_tar_skins()
    {
        $tar = new Archive_Tar('STDOUT');
        $this->startDownload("backup.tar");
        $tar->_exceptions = array(".anchors.ini");
        $tar->create("var/html");
        $tar->_close();
    }

    function action_untar_skins()
    {
        $tar = new Archive_Tar('skins.tar');
        if (!$tar->extractModify('var/html','')) {
            die($tar->_error_message);
        }
        // change file permissions
        $files = $tar->listContent();
        foreach ($files as $file) {
            $fn = "var/html/".$file["filename"];
            if (is_dir($fn)) {
                @chmod($fn, get_filesystem_permissions(0777));
            } else {
                @chmod($fn, get_filesystem_permissions(0666));
            }    
        }
        print "OK";
        @unlink('skins.tar');
		die();
    }
    
    function get_files($dir) { 
        if ($handle = opendir($dir)) { 
            while (false !== ($file = readdir($handle))) { 
                if ($file != "." && $file != "..") {
                    if ($dir == '.') {
                        $full_name = $file;
                    } else {
                        $full_name = $dir . "/" . $file;
                    }       
                    if (is_dir($full_name)) {
                        $this->get_files($full_name);
                    } else {
                        $this->count++;
                        echo $full_name . "\r\n"; 
                    }   
                } 
            }
            closedir($handle); 
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
