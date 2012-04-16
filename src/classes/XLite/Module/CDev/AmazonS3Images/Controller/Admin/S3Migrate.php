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

namespace XLite\Module\CDev\AmazonS3Images\Controller\Admin;

/**
 * Amazon S3 migrate 
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
class S3Migrate extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Migrate to Amazon S3 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function doActionMigrateToS3()
    {
        $info = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->findOneBy(array('name' => 'migrateToS3Info'));
        if (!$info) {
            $info = new \XLite\Model\TmpVar;
            $info->setName('migrateToS3Info');
            \XLite\Core\Database::getEM()->persist($info);
        }
        $info->setValue(serialize(array('position' => 0, 'length' => 0)));
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\EventTask::migrateToS3();
    }

    /**
     * Migrate from Amazon S3
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function doActionMigrateFromS3()
    {
        $info = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->findOneBy(array('name' => 'migrateFromS3Info'));
        if (!$info) {
            $info = new \XLite\Model\TmpVar;
            $info->setName('migrateFromS3Info');
            \XLite\Core\Database::getEM()->persist($info);
        }
        $info->setValue(serialize(array('position' => 0, 'length' => 0)));
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\EventTask::migrateFromS3();
    }
}

