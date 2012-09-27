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

namespace XLite\Model\ListNode;

/**
 * Checkout step
 *
 */
class CheckoutStep extends \XLite\Model\ListNode
{
    /**
     * Is checkout step passed or not
     *
     * @var boolean
     */
    protected $isPassed = false;

    /**
     * Name of the widget class for this checkout step
     *
     * @var string
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
     */
    public function checkMode($mode)
    {
        return isset($mode) ? $this->checkKey($mode) : $this->isPassed();
    }

    /**
     * getWidgetClass
     *
     * @return string
     */
    public function getWidgetClass()
    {
        return $this->widgetClass;
    }

    /**
     * isRegularStep
     *
     * @return boolean
     */
    public function isRegularStep()
    {
        return call_user_func(array($this->getWidgetClass(), 'isRegularStep'));
    }

    /**
     * getMode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->getKey();
    }

    /**
     * getTopMessage
     *
     * @return array
     */
    public function getTopMessage()
    {
        return \XLite\Model\Factory::create($this->getWidgetClass())->getTopMessage($this->isPassed());
    }
}
