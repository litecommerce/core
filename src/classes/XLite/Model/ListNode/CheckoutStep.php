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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model\ListNode;

/**
 * Checkout step
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class CheckoutStep extends \XLite\Model\ListNode
{
    /**
     * Is checkout step passed or not 
     * 
     * @var    bool
     * @access protected
     * @since  3.0.0
     */
    protected $isPassed = false;

    /**
     * Name of the widget class for this checkout step 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $widgetClass = null;


    /**
     * __construct 
     * 
     * @param string $key         step mode
     * @param string $widgetClass step widget class name
     * @param bool   $isPassed    if step is passed or not
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct($key, $widgetClass, $isPassed)
    {
        parent::__construct($key);

        $this->isPassed    = $isPassed;
        $this->widgetClass = $widgetClass;
    }

    /**
     * isPassed 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isPassed()
    {
        return $this->isPassed;
    }

    /**
     * checkMode 
     * 
     * @param string $mode current mode
     *  
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function checkMode($mode)
    {
        return isset($mode) ? $this->checkKey($mode) : $this->isPassed();
    }

    /**
     * getWidgetClass 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getWidgetClass()
    {
        return $this->widgetClass;
    }

    /**
     * isRegularStep 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isRegularStep()
    {
        return call_user_func(array($this->getWidgetClass(), 'isRegularStep'));
    }

    /**
     * getMode 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getMode()
    {
        return $this->getKey();
    }

    /**
     * getTopMessage 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getTopMessage()
    {
        return \XLite\Model\Factory::create($this->getWidgetClass())->getTopMessage($this->isPassed());
    }
}
