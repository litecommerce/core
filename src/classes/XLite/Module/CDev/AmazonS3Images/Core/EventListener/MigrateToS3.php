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
 * Migrate to Amazon S3
 * 
 */
class MigrateToS3 extends \XLite\Core\EventListener\Base\Countable
{
    const CHUNK_LENGTH = 50;

    /**
     * Get event name
     *
     * @return string
     */
    protected function getEventName()
    {
        return 'migrateToS3';
    }

    /**
     * Process item
     *
     * @param mixed $item Item
     *
     * @return boolean
     */
    protected function processItem($item)
    {
        $result = false;
        $path = tempnam(LC_DIR_TMP, 'migrate_file');
        file_put_contents($path, $item->getBody());

        if (\Includes\Utils\FileManager::isExists($path)) {
            $localPath = $item->isURL() ? null : $item->getStoragePath();
            $result = $item->loadFromLocalFile($path, $item->getFileName() ?: basename($item->getPath()));
            if ($result && $localPath && \Includes\Utils\FileManager::isExists($localPath)) {
                \Includes\Utils\FileManager::deleteFile($localPath);
            }
            \Includes\Utils\FileManager::deleteFile($path);
        }

        if (!$result) {
            if (!isset($this->record['s3_error_count'])) {
                $this->record['s3_error_count'] = 0;
            }
            $this->record['s3_error_count']++;
            \XLite\Logger::getInstance()->log(
                'Couldn\'t move image ' . $item->getPath() . ' (local file system to Amazon S3)',
                LOG_ERR
            );
        }

        return true;
    }

    /**
     * Check step valid state
     *
     * @return boolean
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
     */
    protected function getLength()
    {
        $count = 0;

        foreach (\XLite\Model\Repo\Base\Image::getManagedRepositories() as $class) {
            $count += \XLite\Core\Database::getRepo($class)->countNoS3Images();
        }

        return $count;
    }

    /**
     * Get items
     *
     * @return array
     */
    protected function getItems()
    {
        $length = static::CHUNK_LENGTH;
        $chunk = array();

        foreach (\XLite\Model\Repo\Base\Image::getManagedRepositories() as $class) {
            if (0 < \XLite\Core\Database::getRepo($class)->countNoS3Images()) {
                $chunk = array_merge($chunk, \XLite\Core\Database::getRepo($class)->findNoS3Images($length));
                if (count($chunk) < $length) {
                    $length = static::CHUNK_LENGTH - count($chunk);

                } else {
                    break;
                }
            }
        }

        return $chunk;
    }

    /**
     * Finish task
     *
     * @return void
     */
    protected function finishTask()
    {
        parent::finishTask();

        if (isset($this->record['s3_error_count']) && 0 < $this->record['s3_error_count']) {
            $this->errors[] = static::t('Couldn\'t move X images. See log for details.', array('count' => $this->record['s3_error_count']));
        }
    }

}

