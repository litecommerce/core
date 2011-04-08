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
 * PHP version 5.3.0 
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class BackupRestore extends \XLite\Controller\Admin\AAdmin
{
    /**
     * pages 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $pages = array(
        'db_backup'  => 'Backup database',
        'db_restore' => 'Restore database',
    );

    /**
     * params 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $params = array('target', 'page');

    /**
     * page 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $page = 'db_backup';

    /**
     * pageTemplates 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $pageTemplates = array(
        'db_backup'  => 'db/backup.tpl',
        'db_restore' => 'db/restore.tpl',
    );

    /**
     * sqldumpFile 
     * 
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $sqldumpFile = null;


    /**
     * Constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct();

        if (LC_DEVELOPER_MODE) {
            $this->pages['pack_distr'] = 'Pack distr';
        }
    }

    /**
     * File size limit 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getUploadMaxFilesize()
    {
        return ini_get('upload_max_filesize');
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Backup/Restore';
    }

    /**
     * handleRequest 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function handleRequest()
    {
        $request = \XLite\Core\Request::getInstance();

        if ($request->isPost()) {
            set_time_limit(0);
        }

        $this->sqldumpFile = LC_BACKUP_DIR . 'sqldump.sql.php';

        if (LC_DEVELOPER_MODE && !isset($request->action) && 'pack_distr' === $request->page) {
            $request->action = $request->page;
        }

        parent::handleRequest();
    }

    /**
     * isFileExists 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isFileExists()
    {
        return file_exists($this->sqldumpFile);
    }


    /**
     * Add part to the location nodes list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('Backup/Restore', $this->buildURL('backup_restore'));
    }

    /**
     * isDirExists 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isFileWritable()
    {
        return
            $this->isDirExists() 
            && (
                !$this->isFileExists() 
                || ($this->isFileExists() && is_writable($this->sqldumpFile))
            );
    }

    /**
     * doActionBackup 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionBackup()
    {
        $verbose  = false;
        $destfile = null; // write to 'stdout' by default

        if (\XLite\Core\Request::getInstance()->write_to_file) {
            
            $destFile = $this->sqldumpFile;
            $verbose  = true;

            //if (isset($this->mode) && 'cp' == $this->mode) {
                $this->startDump();
            //}

        } else {
            $destFile = LC_BACKUP_DIR . sprintf('sqldump.backup.%d.sql', time());
            $this->startDownload('db_backup.sql');
        }

        // Make database backup and store it in $this->sqldumpFile file
        $result = \XLite\Core\Database::getInstance()->exportSQLToFile($destFile, $verbose);

        if (\XLite\Core\Request::getInstance()->write_to_file) {
            echo ('<br /><b>' . $this->t('Database backup created successfully') . '</b><br />');

        } else {
            readfile($destFile);
            unlink($destFile);
            exit ();
        }
    }

    /**
     * doActionDelete 
     * 
     * @return void
     * @throws
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDelete()
    {
        if (file_exists($this->sqldumpFile)) {

            if (!@unlink($this->sqldumpFile)) {
                throw new \Exception($this->t('Unable to delete file') . ' ' . $this->sqldumpFile);
            }
        }
    }

    /**
     * doActionRestoreFromUploadedFile 
     * 
     * @return void
     * @throws 
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionRestoreFromUploadedFile()
    {
        // Check uploaded file with SQL data 
        if (isset($_FILES['userfile']) && !empty($_FILES['userfile']['tmp_name']) ) {

            $sqlFile = LC_TMP_DIR . sprintf('sqldump.uploaded.%d.sql', time());

            $tmpFile = $_FILES['userfile']['tmp_name'];

            if (!move_uploaded_file($tmpFile, $sqlFile)) {
                throw new \Exception($this->t('Error of uploading file.'));
            }

            $this->restoreDatabase($sqlFile);

            // Remove source SQL-file if it was uploaded
            unlink($sqlFile);
        }

        // Do not update session, etc.
        exit ();
    }

    /**
     * doActionRestoreFromLocalFile
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionRestoreFromLocalFile()
    {
        $this->restoreDatabase($this->sqldumpFile);

        // Do not update session, etc.
        exit ();
    }

    /**
     * doActionPackDistr 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionPackDistr()
    {
        \Includes\Utils\PHARManager::packCore(new \XLite\Core\Pack\Distr());
    }

    /**
     * Common restore database method used by actions
     * 
     * @param mixed $sqlFile File with SQL data for loading into database
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function restoreDatabase($sqlFile)
    {
        $result = false;

        // File to create temporary backup to be able rollback database
        $backupSQLFile = LC_BACKUP_DIR . sprintf('sqldump.backup.%d.sql', time());

        // Make the process of restoring database verbose
        $verbose = true;

        // Start

        $this->startDump();

        // Making the temporary backup file
        echo ($this->t('Making backup of the current database state...'));

        $result = \XLite\Core\Database::getInstance()->exportSQLToFile($backupSQLFile, $verbose);

        // Loading specified SQL-file to the database
        echo ('<br /><br />' . $this->t('Loading the database from file...'));

        $result = \Includes\Utils\Database::uploadSQLFromFile($sqlFile, $verbose);

        if ($result) {
            // If file has been loaded into database successfully
            $message = $this->t('Database restored successfully!');

            // Prepare the cache rebuilding
            \XLite::setCleanUpCacheFlag(true);

        } else {
            // If an error occured while loading file into database
            $message
                = $this->t('The database has not been restored because of the errors');

            // Restore database from temporary backup
            echo ('<br /><br />' . $this->t('Restoring database from the backup...'));

            \Includes\Utils\Database::uploadSQLFromFile($backupSQLFile, $verbose);
        }

        // Display the result message
        echo ('<br /><br />' . $message . '<br />');

        // Display Javascript to cancel scrolling page to bottom
        func_refresh_end();

        // Display the bottom HTML part
        $this->displayPageFooter();

        // Remove temporary backup file
        unlink($backupSQLFile);

        return $result;
    }

    /**
     * getPageReturnURL 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPageReturnURL()
    {
        $url = array();

        switch (\XLite\Core\Request::getInstance()->action) {

            case 'backup':
                $url[] = '<a href="admin.php?target=backup_restore&page=db_backup">Return to admin interface.</a>';
                break;

            case 'restore_from_uploaded_file':
            case 'restore_from_local_file':
                $url[] = '<a href="admin.php?target=backup_restore&page=db_restore">Return to admin interface.</a>';
                break;

            default:
                $url = parent::getPageReturnURL();
        }

        return $url;
    }
}
