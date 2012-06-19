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

namespace XLite\Module\CDev\AmazonS3Images\View;

/**
 * Migrate images
 * 
 * @see   ____class_see____
 * @since 1.0.19
 *
 * @ListChild (list="crud.settings.footer", zone="admin", weight="100")
 */
class Migrate extends \XLite\View\AView
{
    /**
     * Return list of allowed targets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();

        $list[] = 'module';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/CDev/AmazonS3Images/migrate.css';

        return $list;
    }

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'modules/CDev/AmazonS3Images/migrate.js';

        return $list;
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/AmazonS3Images/migrate.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && 'CDev\\AmazonS3Images' == $this->getModule()->getActualName()
            && \XLite\Module\CDev\AmazonS3Images\Core\S3::getInstance()->isValid();
    }

    /**
     * Get migration process started code
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getMigrateStarted()
    {
        $result = false;

        $state = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->getEventState('migrateFromS3');
        if ($state && !\XLite\Core\Database::getRepo('XLite\Model\TmpVar')->isFinishedEventState('migrateFromS3')) {
            $result = 'migrateFromS3';
        }

        if (!$result) {
            $state = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->getEventState('migrateToS3');
            if ($state && !\XLite\Core\Database::getRepo('XLite\Model\TmpVar')->isFinishedEventState('migrateToS3')) {
                $result = 'migrateToS3';
            }
        }

        return $result;
    }

    /**
     * Get migrate percent
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getPercentMigrate()
    {
        $percent = 0;

        $info = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->getEventState('migrateFromS3');

        if ($info) {
            $percent = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->getEventStatePercent('migrateFromS3');
        }

        if (!$info || 100 == $percent) {
            $info = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->getEventState('migrateToS3');

            if ($info) {
                $percent = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->getEventStatePercent('migrateToS3');
            }
        }

        return $percent;
    }

    // {{{ Migrate from S3

    /**
     * Check - has S3 images or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function hasS3Images()
    {
        $result = true;

        foreach (\XLite\Model\Repo\Base\Image::getManagedRepositories() as $class) {
            if (0 < \XLite\Core\Database::getRepo($class)->countNoS3Images()) {
                $result = false;
                break;
            }
        }

        if ($result) {
            $result = false;
            foreach (\XLite\Model\Repo\Base\Image::getManagedRepositories() as $class) {
                if (0 < \XLite\Core\Database::getRepo($class)->countS3Images()) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Check migrate from Amazon S3 form visibility
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function isMigrateFromS3Visible()
    {
        return !$this->getMigrateStarted() && $this->hasS3Images();
    }

    // }}}

    // {{{ Migrate to S3

    /**
     * Check - has non-S3 images  or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function hasNoS3Images()
    {
        $result = false;

        foreach (\XLite\Model\Repo\Base\Image::getManagedRepositories() as $class) {
            if (0 < \XLite\Core\Database::getRepo($class)->countNoS3Images()) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Check migrate to Amazon S3 form visibility
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function isMigrateToS3Visible()
    {
        return !$this->getMigrateStarted() && $this->hasNoS3Images();
    }

    // }}}
}

