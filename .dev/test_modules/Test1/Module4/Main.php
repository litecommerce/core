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

namespace XLite\Module\Test1\Module4;

/**
 * Main 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @var    string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAuthorName()
    {
        return 'Test author 1';
    }

    /**
     * Module name
     *
     * @var    string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getModuleName()
    {
        return 'Test module 4';
    }

    /**
     * Module version
     *
     * @return string
     * @access public
     * @since  1.0.0
     */
    public static function getMinorVersion()
    {
        return '0';
    }

    /**
     * Module description
     *
     * @return string
     * @access public
     * @since  1.0.0
     */
    public static function getDescription()
    {
        return 'Test module 4 description';
    }

    /**
     * Return module dependencies
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getDependencies()
    {
        $list = parent::getDependencies();
        $list[] = 'Test1\Module1';

        return $list;
    }
}
