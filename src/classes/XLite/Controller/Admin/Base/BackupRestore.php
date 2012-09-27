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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Admin\Base;

/**
 * Base controller for Backup/Restore section
 *
 */
abstract class BackupRestore extends \XLite\Controller\Admin\AAdmin
{
    /**
     * sqldumpFile
     *
     * @var mixed
     */
    protected $sqldumpFile = null;


    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Backup/Restore';
    }

    /**
     * handleRequest
     *
     * @return void
     */
    public function handleRequest()
    {
        if (\XLite\Core\Request::getInstance()->isPost()) {
            set_time_limit(0);
        }

        $this->sqldumpFile = LC_DIR_BACKUP . 'sqldump.sql.php';

        parent::handleRequest();
    }

    /**
     * isFileExists
     *
     * @return boolean
     */
    public function isFileExists()
    {
        return file_exists($this->sqldumpFile);
    }

    /**
     * isFileWritable
     *
     * @return boolean
     */
    public function isFileWritable()
    {
        return
            $this->isDirExists()
            && (
                !$this->isFileExists()
                || ($this->isFileExists() && is_writable($this->sqldumpFile))
            );
    }

    /**
     * isDirExists
     *
     * @return boolean
     */
    protected function isDirExists()
    {
        $result = is_dir(LC_DIR_BACKUP);

        if (!$result) {
            \Includes\Utils\FileManager::mkdirRecursive(LC_DIR_BACKUP);
        }

        $result = is_dir(LC_DIR_BACKUP) && is_writable(LC_DIR_BACKUP);

        return $result;
    }
}
