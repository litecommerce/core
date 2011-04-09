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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Includes
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace Includes;

/**
 * No DB store
 * NOTE - this class is abstract due to prevent its instantiation
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      1.0.0
 */
abstract class NoDBStore
{
    const INSTALL_SCRIPT = 'install.php';

    protected static $installScript;


    public static function showStorePage()
    {
        echo 'It seems that DB is corrupted <br />';

        echo self::isInstallationScript() ? 'Install script : <a href="' . self::INSTALL_SCRIPT . '">' . self::INSTALL_SCRIPT . '</a>' : '';

        exit(1);
    }

    protected static function isInstallationScript()
    {
        return file_exists(self::getInstallScript());
    }

    protected static function getInstallScript()
    {
        return isset(self::$installScript) ? self::$installScript : (self::$installScript = LC_ROOT_DIR . self::INSTALL_SCRIPT);
    }

}
