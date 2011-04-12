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
 * Controller for Database backup page
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class DbBackup extends \XLite\Controller\Admin\Base\BackupRestore
{
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

            $this->startDump();

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
     * getPageReturnURL 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPageReturnURL()
    {
        $url = array();

        if ('backup' == \XLite\Core\Request::getInstance()->action) {
            $url[] = '<a href="admin.php?target=db_backup">Return to admin interface.</a>';
        
        } else { 
            $url = parent::getPageReturnURL();
        }

        return $url;
    }
}
