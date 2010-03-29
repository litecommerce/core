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
* Class description.
*
* @package Dialog
* @access public
* @version $Id$
*
*/
class XLite_Controller_Admin_Db extends XLite_Controller_Admin_Abstract
{
	/**
	 * File size limit 
	 * 
	 * @return mixed
	 * @access public
	 * @since  3.0.0
	 */
	public function getUploadMaxFilesize()
	{
		return ini_get('upload_max_filesize');
	}


	public $params = array('target', 'page');	
	public $page = "db_backup";	
	public $pages = array( "db_backup" 	=> "Backup database",
						"db_restore" 	=> "Restore database");	
	public $pageTemplates = array(	"db_backup" 	=> "db/backup.tpl",
								"db_restore"	=> "db/restore.tpl");	
   	public $upload_max_filesize;	
   	public $sqldump_dir = SQL_DUMP_DIR;	
    public $sqldump_file = SQL_DUMP_FILE;
    
    function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            @set_time_limit(1800);
        }

        if (
        	$this->action == "restore" 
        	&& 
        	(
            	(!isset($this->local_file) && !$this->checkUploadedFile())
            	||
            	(isset($this->local_file) && !$this->isFileExists())
        	)
        ) {
        	$this->set("valid", false);
        	$this->set("invalid_file", true);
        }
        if (
        	$this->action == "backup" 
        	&& 
			(intval(strval($this->write_to_file)) != 0 && !$this->isFileWritable())
        ) {
        	$this->set("valid", false);
        	$this->set("invalid_file", true);
        }

        parent::handleRequest();
    }

    function isDirExists()
    {
    	return (@is_dir($this->sqldump_dir) && @is_writable($this->sqldump_dir));
    }

    function isFileExists()
    {
        return file_exists($this->sqldump_file);
    }

    function isFileWritable()
    {
        return $this->isDirExists() && (!$this->isFileExists() || ($this->isFileExists() && @is_writable($this->sqldump_file)));
    }

    function action_backup()
    {
        $verbose  = false;
        $destfile = null; // write to 'stdout' by default

        if ($_POST["write_to_file"]) {
            $destfile = $this->sqldump_file;
            $verbose  = true;
            (isset($this->mode) && $this->mode == "cp") or $this->startDump();
        } else {
            $this->startDownload("db_backup.sql.php");
        }
        $this->db->backup($destfile, $verbose);
        if (isset($_POST["write_to_file"])) {
            if (isset($this->mode) && $this->mode == "cp") {
                // Windows Control Panel mode. suppress "back" message.
                die("OK");
            } else {
        		if ($_POST["write_to_file"]) {
            		echo "<br><b>Database backup created successfully</b><br>";
            	}
                $this->set("silent", true);
            }    
        } else {
    		exit;
    	}
    }

    function action_delete()
    {
        if (file_exists($this->sqldump_file)) unlink($this->sqldump_file) or die("Unable to delete file $this->sqldump_file");
        if (isset($this->mode) && $this->mode == "cp") {
            die("OK");
        }
    }

    function action_restore()
    {
        // restore from FS by default
        $srcfile = $this->sqldump_file; 
        $mode = "file";

        // check whether to restore from file upload
		mkdirRecursive(SQL_UPLOAD_DIR);
        if (!isset($_POST['local_file']))
        { 
            $upload = new XLite_Model_Upload($_FILES['userfile']);
            $srcfile = SQL_UPLOAD_DIR.$upload->getName();
            if (!$upload->move($srcfile)) {
                $this->error = $upload->getErrorMessage();
                $this->set('valid', false);
                return;
            }
            $mode = "upload";
        }
        if ($this->get("mode") != "cp") {
            $this->startDump();
        }
        $error = $this->db->restore($srcfile);
        if ($mode == "upload") @unlink($srcfile);
        if (!$error){
            echo "<br />Some errors occurred during restoring the database. The database has not been restored!<br />";
        } else {
            echo "<br>Database restored successfully!<br>";
        }
        if (isset($this->mode) && $this->mode == "cp") {
            // Windows Control Panel mode. suppress "back" message.
            echo "OK";
        } else {
			$this->displayPageFooter();
			func_refresh_end();
        }
        // do not update session, etc.
        exit();
    }

	function getPageReturnUrl()
	{
		$url = "";
		switch ($this->action) {
			case "backup":
				$url = array('<a href="admin.php?target=db&page=db_backup"><u>Return to admin interface.</u></a>');
			break;
			case "restore":
				$url = array('<a href="admin.php?target=db&page=db_restore"><u>Return to admin interface.</u></a>');
			break;
			default:
				$url = parent::getPageReturnUrl();
		}

		return $url;
	}

}

