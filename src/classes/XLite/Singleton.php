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

namespace XLite;

/**
 * Singleton 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Singleton
{
    /**
     * xlite 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    public static $xlite = '\XLite';

    /**
     * request 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    public static $request = '\XLite\Core\Request';

    /**
     * layout 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    public static $layout = '\XLite\Core\Layout';

    /**
     * session 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    public static $session = '\XLite\Core\Session';

    /**
     * config 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    public static $config = '\XLite\Core\Config';

    /**
     * flexy 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    public static $flexy = '\XLite\Core\FlexyCompiler';


    /**
     * Initialize variables
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function init()
    {
        foreach (get_class_vars(get_called_class()) as $name => $class) {
            static::$$name = call_user_func(array($class, 'getInstance'));
        }
    }
}
