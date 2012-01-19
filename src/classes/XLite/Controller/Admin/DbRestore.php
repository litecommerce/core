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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Controller for Database restore page
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class DbRestore extends \XLite\Controller\Admin\Base\BackupRestore
{
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

            $sqlFile = LC_DIR_TMP . sprintf('sqldump.uploaded.%d.sql', time());

            $tmpFile = $_FILES['userfile']['tmp_name'];

            if (!move_uploaded_file($tmpFile, $sqlFile)) {
                throw new \Exception(static::t('Error of uploading file.'));
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
        $backupSQLFile = LC_DIR_BACKUP . sprintf('sqldump.backup.%d.sql', time());

        // Make the process of restoring database verbose
        $verbose = true;

        // Start

        $this->startDump();

        // Making the temporary backup file
        echo (static::t('Making backup of the current database state...'));

        $result = \XLite\Core\Database::getInstance()->exportSQLToFile($backupSQLFile, $verbose);

        // Loading specified SQL-file to the database
        echo ('<br /><br />' . static::t('Loading the database from file...'));

        $result = \Includes\Utils\Database::uploadSQLFromFile($sqlFile, $verbose);

        if ($result) {
            // If file has been loaded into database successfully
            $message = static::t('Database restored successfully!');

            // Prepare the cache rebuilding
            \XLite::setCleanUpCacheFlag(true);

        } else {
            // If an error occured while loading file into database
            $message
                = static::t('The database has not been restored because of the errors');

            // Restore database from temporary backup
            echo ('<br /><br />' . static::t('Restoring database from the backup...'));

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

            case 'restore_from_uploaded_file':
            case 'restore_from_local_file':
                $url[] = '<a href="admin.php?target=db_restore">'.static::t('Return to admin interface.').'</a>';
                break;

            default:
                $url = parent::getPageReturnURL();
        }

        return $url;
    }
}
