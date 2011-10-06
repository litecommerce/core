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

namespace XLite\Controller\Customer;

/**
 * Storage
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Storage extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Storage 
     * 
     * @var   \XLite\Model\Base\Storage
     * @see   ____var_see____
     * @since 1.0.11
     */
    protected $storage;

    /**
     * Check if current page is accessible
     *
     * @return boolean
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected function checkAccess()
    {
        return parent::checkAccess() && ('download' != $this->getAction() || $this->getStorage());
    }

    /**
     * Download
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDownload()
    {
        $this->silent = true;
        header('Content-Type: '. $this->getStorage()->getMime());
        header('Content-Size: '. $this->getStorage()->getSize());
        header('Content-Disposition: attachment; filename="' . addslashes($this->getStorage()->getFileName()) . '";');
        $this->readStorage($this->getStorage());
    }

    /**
     * Get storage 
     * 
     * @return \XLite\Model\Base\Storage
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function getStorage()
    {
        if (
            !isset($this->storage)
            || !is_object($this->storage)
            || !($this->storage instanceof \XLite\Model\Base\Storage)
        ) {
            $class = \XLite\Core\Request::getInstance()->storage;
            if (\XLite\Core\Operator::isClassExists($class)) {
                $id = \XLite\Core\Request::getInstance()->id;
                $this->storage = \XLite\Core\Database::getRepo($class)->find($id);
                if (!$this->storage->isFileExists()) {
                    $this->storage = null;
                }
            }
        }

        return $this->storage;
    }

    /**
     * Read storage 
     * 
     * @param \XLite\Model\Base\Storage $storage Storage
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function readStorage(\XLite\Model\Base\Storage $storage)
    {
        $storage->readOutput();
    }
}
