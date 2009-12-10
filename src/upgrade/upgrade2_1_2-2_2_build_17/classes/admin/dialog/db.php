<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
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
* @version $Id: db.php,v 1.1 2006/07/11 06:38:12 sheriff Exp $
*
*/
class Admin_Dialog_db extends Admin_Dialog
{
	var $params = array('target', 'page');
	var $page = "db_backup";
	var $pages = array( "db_backup" 	=> "Backup database",
						"db_restore" 	=> "Restore database");
	var $pageTemplates = array(	"db_backup" 	=> "db/backup.tpl",
								"db_restore"	=> "db/restore.tpl");
   	var $upload_max_filesize;
    var $sqldump_file = SQL_DUMP_FILE;
    
    function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            @set_time_limit(1800);
        }

        if ($this->action == "restore" && !$this->checkUploadedFile()) {
        	$this->set("valid", false);
        	$this->set("invalid_file", true);
        }

        parent::handleRequest();
    }

    function isFileExists()
    {
        return file_exists($this->sqldump_file);
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
            echo "<br>Database backup created successfully<br>";
            if (isset($this->mode) && $this->mode == "cp") {
                // Windows Control Panel mode. suppress "back" message.
                die("OK");
            } else {
                echo "<a href=\"admin.php?target=db&page=db_backup\">Return to admin interface.</a>";
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
            $srcfile = SQL_UPLOAD_DIR . $_FILES['userfile']['name'];
            $mode = "upload";
        }
        if ($this->get("mode") != "cp") {
            $this->startDump();
        }
        $this->db->restore($srcfile);
        if ($mode == "upload") @unlink($srcfile);
        echo "<br>Database restored successfully!<br>";
        if (isset($this->mode) && $this->mode == "cp") {
            // Windows Control Panel mode. suppress "back" message.
            echo "OK";
        } else {
            echo "<a href=\"admin.php?target=db&page=db_restore\">Return to admin interface.</a>";
        }
        // do not update session, etc.
        exit();
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
