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

namespace XLite\View\Upgrade\Step\Completed;

/**
 * Backup 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="admin.center", weight="100", zone="admin")
 */
class Backup extends \XLite\View\Upgrade\Step\Completed\ACompleted
{
    /**
     * Get directory where template is located (body.tpl)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return parent::getDir() . '/backup';
    }

    /**
     * Return internal list name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getListName()
    {
        return parent::getListName() . '.backup';
    }

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return 'Do not close this page!';
    }

    /**
     * Info message
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDescription()
    {
        return static::t(
            'The upgrade is completed. Please, do not close this page until you check '
            . '<a href="{{url}}">your web site</a> and check that everything works properly. '
            . 'If there are some critical errors occured you can do the following',
            array('url' => \Includes\Utils\URLManager::getShopURL())
        );
    }

    /**
     * Get an action URL
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSoftResetURL()
    {
        return \Includes\SafeMode::getResetURL(true);
    }

    /**
     * Get an action URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHardResetURL()
    {
        return \Includes\SafeMode::getResetURL(false);
    }
}
