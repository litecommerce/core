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

namespace XLite\Core\EventDriver;

/**
 * Abstract event driver 
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
abstract class AEventDriver extends \XLite\Base
{
    /**
     * Fire event
     * 
     * @param string $name      Event name
     * @param array  $arguments Arguments OPTIONAL
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    abstract public function fire($name, array $arguments = array());

    /**
     * Check driver
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public static function isValid()
    {
        return true;
    }

    /**
     * Get driver code 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.19
     */
    public static function getCode()
    {
        return null;
    }

    /**
     * Constructor
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function __construct()
    {
    }
}
