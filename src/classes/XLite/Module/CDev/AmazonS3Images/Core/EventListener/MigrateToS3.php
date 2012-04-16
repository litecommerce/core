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
 * @since     1.0.19
 */

namespace XLite\Module\CDev\AmazonS3Images\Core\EventListener;

/**
 * Migrate to Amazon S3
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
class MigrateToS3 extends \XLite\Core\EventListener\AEventListener
{
    const CHUNK_LENGTH = 100;

    /**
     * Handle event (internal, after checking)
     *
     * @param string $name      Event name
     * @param array  $arguments Event arguments OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function handleEvent($name, array $arguments)
    {
        if (0 < $this->getLength() && \XLite\Module\CDev\AmazonS3Images\Core\S3::getInstance()->isValid()) {

            $info = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->findOneBy(array('name' => 'migrateToS3Info'));
            if (!$info) {
                $info = new \XLite\Model\TmpVar;
                $info->setName('migrateToS3Info');
                \XLite\Core\Database::getEM()->persist($info);
            }
            $rec = $info->getValue() ? unserialize($info->getValue()) : array('position' => 0, 'length' => 0);

            if (0 == $rec['length']) {
                $rec['length'] = $this->getLength();
            }

            foreach ($this->getChunk() as $image) {
                $path = tempnam(LC_DIR_TMP, 'migrate_file');
                file_put_contents($path, $image->getBody());

                if (file_exists($path)) {
                    if ($image->loadFromLocalFile($path, $image->getFileName() ?: basename($image->getPath()))) {
                        $rec['position']++;
                        $info->setValue(serialize($rec));
                        \XLite\Core\Database::getEM()->flush();
                    }
                    unlink($path);
                }
            }

            \XLite\Core\Database::getEM()->flush();

            if (0 < $this->getLength()) {
                \XLite\Core\EventTask::migrateToS3();
            }
        }

        return true;
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
            $count += \XLite\Core\Database::getRepo($class)->countNoS3Images();
        }

        return $count;
    }

    /**
     * Get images chunk 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getChunk()
    {
        $chunk = array();

        foreach (\XLite\Model\Repo\Base\Image::getManagedRepositories() as $class) {
            if (0 < \XLite\Core\Database::getRepo($class)->countNoS3Images()) {
                $chunk = \XLite\Core\Database::getRepo($class)->findNoS3Images(static::CHUNK_LENGTH);
                break;
            }
        }

        return $chunk;
    }
}

