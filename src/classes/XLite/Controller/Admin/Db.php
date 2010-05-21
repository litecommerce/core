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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
        	$this->set('valid', false);
        	$this->set('invalid_file', true);
        }
        if (
        	$this->action == "backup" 
        	&& 
            (intval(strval($this->write_to_file)) != 0 && !$this->isFileWritable())
        ) {
        	$this->set('valid', false);
        	$this->set('invalid_file', true);
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

        if (XLite_Core_Request::getInstance()->write_to_file) {
            $destfile = $this->sqldump_file;
            $verbose  = true;
            (isset($this->mode) && $this->mode == "cp") or $this->startDump();
        } else {
            $this->startDownload('db_backup.sql.php');
        }
        $this->db->backup($destfile, $verbose);
        if (isset(XLite_Core_Request::getInstance()->write_to_file)) {
            if (isset($this->mode) && $this->mode == "cp") {
                // Windows Control Panel mode. suppress "back" message.
                die('OK');
            } else {
        		if (XLite_Core_Request::getInstance()->write_to_file) {
            		echo "<br><b>Database backup created successfully</b><br>";
            	}
                $this->set('silent', true);
            }
        } else {
    		exit;
    	}
    }

    function action_delete()
    {
        if (file_exists($this->sqldump_file)) unlink($this->sqldump_file) or die("Unable to delete file $this->sqldump_file");
        if (isset($this->mode) && $this->mode == "cp") {
            die('OK');
        }
    }

    function action_restore()
    {
        // restore from FS by default
        $srcfile = $this->sqldump_file;
        $mode = "file";

        // check whether to restore from file upload
        mkdirRecursive(SQL_UPLOAD_DIR);
        if (!isset(XLite_Core_Request::getInstance()->local_file))
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
        if ($this->get('mode') != "cp") {
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
