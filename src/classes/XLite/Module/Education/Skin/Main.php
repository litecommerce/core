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

namespace XLite\Module\Education\Skin;

/**
 * Skin customization module
 *
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'Creative Development LLC';
    }

    /**
     * Module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'Skin customization';
    }

    /**
     * Get module major version
     *
     * @return string
     */
    public static function getMajorVersion()
    {
        return '1.1';
    }

    /**
     * Module version
     *
     * @return string
     */
    public static function getMinorVersion()
    {
        return '0';
    }

    /**
     * Module description
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'Skin customization module for educational use only';
    }

    /**
     * Method to initialize concrete module instance
     *
     * @return void
     */
    public static function init()
    {
        parent::init();

        /**
         * You could use the complete call of addSkin method:
         *
         * \XLite\Core\Layout::getInstance()->addSkin('education.skin.example', \XLite::CUSTOMER_INTERFACE);
         *
         * or any other interface in this list:
         *
         * \XLite::ADMIN_INTERFACE
         * \XLite::CONSOLE_INTERFACE
         * \XLite::COMMON_INTERFACE
         * \XLite::MAIL_INTERFACE
         * \XLite::CUSTOMER_INTERFACE
         *
         * More information you will find in the developer documentation : Substitutional skin explanation
         *
         */
        \XLite\Core\Layout::getInstance()->addSkin('education.skin.example');
    }

}
