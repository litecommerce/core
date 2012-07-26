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

namespace XLite\Model\Shipping;

/**
 * Shipping rate model
 *
 */
class Rate extends \XLite\Base\SuperClass
{
    /**
     * Shipping method object
     *
     * @var \XLite\Model\Shipping\Method
     */
    protected $method = null;

    /**
     * Shipping markup object
     *
     * @var \XLite\Model\Shipping\Markup
     */
    protected $markup = null;

    /**
     * Base rate value
     *
     * @var float
     */
    protected $baseRate = 0;

    /**
     * Markup rate value
     *
     * @var float
     */
    protected $markupRate = 0;

    /**
     * Rate's extra data (real-time rate calculation's details)
     *
     * @var \XLite\Core\CommonCell
     */
    protected $extraData = null;

    /**
     * Public class constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * getMethod
     *
     * @return \XLite\Model\Shipping\Method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * setMethod
     *
     * @param \XLite\Model\Shipping\Method $method Shipping method object
     *
     * @return void
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * getMarkup
     *
     * @return \XLite\Model\Shipping\Markup
     */
    public function getMarkup()
    {
        return $this->markup;
    }

    /**
     * setMarkup
     *
     * @param \XLite\Model\Shipping\Markup $markup Shipping markup object
     *
     * @return void
     */
    public function setMarkup($markup)
    {
        $this->markup = $markup;
    }

    /**
     * getBaseRate
     *
     * @return float
     */
    public function getBaseRate()
    {
        return $this->baseRate;
    }

    /**
     * setBaseRate
     *
     * @param float $baseRate Base rate value
     *
     * @return void
     */
    public function setBaseRate($baseRate)
    {
        $this->baseRate = $baseRate;
    }

    /**
     * getMarkupRate
     *
     * @return float
     */
    public function getMarkupRate()
    {
        return $this->markupRate;
    }

    /**
     * setMarkupRate
     *
     * @param float $markupRate Markup rate value
     *
     * @return void
     */
    public function setMarkupRate($markupRate)
    {
        $this->markupRate = $markupRate;
    }

    /**
     * getExtraData
     *
     * @return \XLite\Core\CommonCell
     */
    public function getExtraData()
    {
        return $this->extraData;
    }

    /**
     * setExtraData
     *
     * @param \XLite\Core\CommonCell $extraData Rate's extra data
     *
     * @return void
     */
    public function setExtraData(\XLite\Core\CommonCell $extraData)
    {
        $this->extraData = $extraData;
    }

    /**
     * getTotalRate
     *
     * @return float
     */
    public function getTotalRate()
    {
        return $this->getBaseRate() + $this->getMarkupRate();
    }

    /**
     * Get taxable basis 
     * 
     * @return float
     */
    public function getTaxableBasis()
    {
        return $this->getBaseRate() + $this->getMarkupRate();
    }

    /**
     * getMethodId
     *
     * @return integer
     */
    public function getMethodId()
    {
        $method = $this->getMethod();

        return isset($method) ? $this->getMethod()->getMethodId() : null;
    }

    /**
     * getMethodName
     *
     * @return string
     */
    public function getMethodName()
    {
        $method = $this->getMethod();

        return isset($method) ? $this->getMethod()->getName() : null;
    }

}
