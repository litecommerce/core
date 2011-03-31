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
 * @since     3.0.0
 */

namespace XLite\Core;

/**
 * Widget data transport
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class WidgetDataTransport extends \XLite\Base
{
    /**
     * Handler to use
     * 
     * @var   \XLite\View\AView|null
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $handler;


    /**
     * Save passed handler
     * 
     * @param \XLite\View\AView|null $handler Passed handler
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    /**
     * Get widget 
     * 
     * @return \XLite\View\AView
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProtectedWidget()
    {
        return $this->handler;
    }

    /**
     * Call handler methods
     * 
     * @param string $method Method to call
     * @param array  $args   Call arguments OPTIONAL
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __call($method, array $args = array())
    {
        return isset($this->handler) ? call_user_func_array(array($this->handler, $method), $args) : null;
    }
}
