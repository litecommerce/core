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

namespace XLite\Module\CDev\AmazonS3Images\Core\EventListener;

/**
 * Migrate from Amazon S3
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
class MigrateFromS3 extends \XLite\Core\EventListener\Base\Countable
{
    const CHUNK_LENGTH = 50;

    /**
     * Get event name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.23
     */
    protected function getEventName()
    {
        return 'migrateFromS3';
    }

    /**
     * Process item
     *
     * @param mixed $item Item
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.23
     */
    protected function processItem($item)
    {
        $result = false;

        $path = tempnam(LC_DIR_TMP, 'migrate_file');
        file_put_contents($path, $item->getBody());

        if (file_exists($path)) {
            $item->setS3Forbid(true);
            $localPath = $item->getStoragePath();
            $result = $item->loadFromLocalFile($path, $item->getFileName() ?: basename($item->getPath()));
            if ($localPath) {
                \XLite\Module\CDev\AmazonS3Images\Core\S3::getInstance()->delete($localPath);
            }
            unlink($path);
        }

        if (!$result) {
            if (!isset($this->record['s3_error_count'])) {
                $this->record['s3_error_count'] = 0;
            }
            $this->record['s3_error_count']++;
            \XLite\Logger::getInstance()->log(
                'Couldn\'t move image ' . $item->getPath() . ' (Amazon S3 to local file system)',
                LOG_ERR
            );
        }

        return true;
    }

    /**
     * Check step valid state
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.23
     */
    protected function isStepValid()
    {
        return parent::isStepValid()
            && \XLite\Module\CDev\AmazonS3Images\Core\S3::getInstance()->isValid();
    }

    /**
     * Get images list length 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getLength()
    {
        $count = 0;

        foreach (\XLite\Model\Repo\Base\Image::getManagedRepositories() as $class) {
            $count += \XLite\Core\Database::getRepo($class)->countS3Images();
        }

        return $count;
    }

    /**
     * Get items
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.23
     */
    protected function getItems()
    {
        $chunk = array();

        foreach (\XLite\Model\Repo\Base\Image::getManagedRepositories() as $class) {
            if (0 < \XLite\Core\Database::getRepo($class)->countS3Images()) {
                $chunk = \XLite\Core\Database::getRepo($class)->findS3Images(static::CHUNK_LENGTH);
                break;
            }
        }

        return $chunk;
    }

    /**
     * Finish task
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.23
     */
    protected function finishTask()
    {
        parent::finishTask();

        if (isset($this->record['s3_error_count']) && 0 < $this->record['s3_error_count']) {
            $this->errors[] = static::t('Couldn\'t move X images. See log for details.', array('count' => $this->record['s3_error_count']));
        }
    }
}

