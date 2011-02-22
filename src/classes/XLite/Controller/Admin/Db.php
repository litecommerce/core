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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Db extends \XLite\Controller\Admin\AAdmin
{
    /**
     * pages 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $pages = array(
        'db_backup'  => 'Backup database',
        'db_restore' => 'Restore database'
    );

    /**
     * params 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $params = array('target', 'page');

    /**
     * page 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $page = 'db_backup';

    /**
     * pageTemplates 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $pageTemplates = array(
        'db_backup'  => 'db/backup.tpl',
        'db_restore' => 'db/restore.tpl'
    );

    /**
     * sqldump_file 
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $sqldump_file = null;


    /**
     * File size limit 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getUploadMaxFilesize()
    {
        return ini_get('upload_max_filesize');
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        return $this->t('DB Backup/Restore');
    }

    /**
     * handleRequest 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            set_time_limit(1800);
        }

        $this->sqldump_file = LC_BACKUP_DIR . 'sqldump.sql.php';

        if ('restore' == $this->action) {

            if (
                (!isset($this->local_file) && !$this->checkUploadedFile()) 
                || (isset($this->local_file) && !$this->isFileExists())
            ) {
                $this->set('valid', false);
                $this->set('invalid_file', true);
            }
        }

        if ('backup' == $this->action && 0 != intval(strval($this->write_to_file)) && !$this->isFileWritable()) {
               $this->set('valid', false);
            $this->set('invalid_file', true);
        }

        parent::handleRequest();
    }

    /**
     * isFileExists 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isFileExists()
    {
        return file_exists($this->sqldump_file);
    }


    /**
     * Add part to the location nodes list
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode($this->t('DB Backup/Restore'), $this->buildURL('db'));
    }

    /**
     * isDirExists 
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isDirExists()
    {
        $result = is_dir(LC_BACKUP_DIR);
    
        if (!$result) {
            \Includes\Utils\FileManager::mkdirRecursive(LC_BACKUP_DIR);
        }

        $result = is_dir(LC_BACKUP_DIR) && is_writable(LC_BACKUP_DIR);

        return $result;
    }

    /**
     * isFileWritable 
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isFileWritable()
    {
        return
            $this->isDirExists() 
            && (
                !$this->isFileExists() 
                || ($this->isFileExists() && is_writable($this->sqldump_file))
            );
    }

    /**
     * doActionBackup 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionBackup()
    {
        $verbose  = false;
        $destfile = null; // write to 'stdout' by default

        if (isset(\XLite\Core\Request::getInstance()->write_to_file)) {
            
            $destfile = $this->sqldump_file;
            $verbose  = true;

            //if (isset($this->mode) && 'cp' == $this->mode) {
                $this->startDump();
            //}

        } else {
            $this->startDownload('db_backup.sql.php');
        }

        $this->db->backup($destfile, $verbose);

        if (isset(\XLite\Core\Request::getInstance()->write_to_file)) {

            if (isset($this->mode) && 'cp' == $this->mode) {
                // Windows Control Panel mode. suppress "back" message.
                die ('OK');

            } else {

                if (\XLite\Core\Request::getInstance()->write_to_file) {
                    echo ('<br /><b>' . $this->t('Database backup created successfully') . '</b><br />');
                }

                $this->set('silent', true);
            }

        } else {
            exit ();
        }
    }

    /**
     * doActionDelete 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        if (file_exists($this->sqldump_file)) {

            if (!@unlink($this->sqldump_file)) {
                die ($this->t('Unable to delete file') . ' ' . $this->sqldump_file);
            }
        }

        if (isset($this->mode) && 'cp' == $this->mode) {
            die ('OK');
        }
    }

    /**
     * doActionRestore 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionRestore()
    {
        // restore from FS by default
        $srcfile = $this->sqldump_file;
        $mode = 'file';

        // check whether to restore from file upload
        \Includes\Utils\FileManager::mkdirRecursive(SQL_UPLOAD_DIR);

        if (!isset(\XLite\Core\Request::getInstance()->local_file)) {

            $upload = new \XLite\Model\Upload($_FILES['userfile']);
            $srcfile = SQL_UPLOAD_DIR . $upload->getName();
            
            if (!$upload->move($srcfile)) {
                $this->error = $upload->getErrorMessage();
                $this->set('valid', false);
                
                return;
            }
            
            $mode = 'upload';
        }

        if ('cp' != $this->get('mode')) {
            $this->startDump();
        }

        $error = $this->db->restore($srcfile);

        if ('upload' == $mode) {
            unlink($srcfile);
        }

        if (!$error) {
            $message = 
                $this->t('Some errors occurred during restoring the database. The database has not been restored!');

        } else {
            $message = $this->t('Database restored successfully!');
        }

        echo ('<br />' . $message . '<br />');

        if (isset($this->mode) && 'cp' == $this->mode) {
            // Windows Control Panel mode. suppress "back" message.
            echo ('OK');

        } else {
            $this->displayPageFooter();
            func_refresh_end();
        }

        // do not update session, etc.
        exit ();
    }

    /**
     * getPageReturnURL 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPageReturnURL()
    {
        $url = array();

        switch ($this->action) {

            case 'backup':
                $url[] = '<a href="admin.php?target=db&page=db_backup">Return to admin interface.</a>';
                break;

            case 'restore':
                $url[] = '<a href="admin.php?target=db&page=db_restore">Return to admin interface.</a>';
                break;

            default:
                $url = parent::getPageReturnURL();
        }

        return $url;
    }
}
