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

namespace XLite\Model\ListNode;

/**
 * Checkout step
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class CheckoutStep extends \XLite\Model\ListNode
{
    /**
     * Is checkout step passed or not 
     * 
     * @var    bool
     * @since  3.0.0
     */
    protected $isPassed = false;

    /**
     * Name of the widget class for this checkout step 
     * 
     * @var    string
     * @since  3.0.0
     */
    protected $widgetClass = null;


    /**
     * __construct 
     * 
     * @param string  $key         Step mode
     * @param string  $widgetClass Step widget class name
     * @param boolean $isPassed    If step is passed or not
     *  
     * @return void
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
     * @return boolean 
     * @since  3.0.0
     */
    public function isPassed()
    {
        return $this->isPassed;
    }

    /**
     * checkMode 
     * 
     * @param string $mode Current mode
     *  
     * @return boolean 
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
     * @since  3.0.0
     */
    public function getWidgetClass()
    {
        return $this->widgetClass;
    }

    /**
     * isRegularStep 
     * 
     * @return boolean 
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
     * @since  3.0.0
     */
    public function getTopMessage()
    {
        return \XLite\Model\Factory::create($this->getWidgetClass())->getTopMessage($this->isPassed());
    }
}
