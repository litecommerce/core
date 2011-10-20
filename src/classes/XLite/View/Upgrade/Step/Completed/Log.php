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

namespace XLite\View\Upgrade\Step\Completed;

/**
 * Log
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="admin.center", weight="400", zone="admin")
 */
class Log extends \XLite\View\Upgrade\Step\Completed\ACompleted
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
        return parent::getDir() . '/log';
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
        return parent::getListName() . '.log';
    }

    /**
     * Get the log file link
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLogFileURL()
    {
        return $this->buildURL('upgrade', 'view_log_file');
    }

    /**
     * Called after the includeCompiledFile()
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function closeView()
    {
        parent::closeView();

        \XLite\Upgrade\Cell::getInstance()->clear(true, true, false);
        \XLite\Upgrade\Cell::getInstance()->setUpgraded(false);
    }
}
