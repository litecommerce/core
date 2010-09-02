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

namespace XLite\Model\Shipping;

/**
 * Shipping rate model
 * 
 * @package    XLite
 * @subpackage Model
 * @see        ____class_see____
 * @since      3.0.0
 */
class Rate extends \XLite\Base\SuperClass
{
    /**
     * Shipping method object
     * 
     * @var    \XLite\Model\Shipping\Method
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $method = null;

    /**
     * Shipping markup object
     * 
     * @var    \XLite\Model\Shipping\Markup
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $markup = null;

    /**
     * Base rate value
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $baseRate = 0;

    /**
     * Markup rate value
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $markupRate = 0;

    /**
     * Rate's extra data (real-time rate calculation's details)
     * 
     * @var    \XLite\Core\CommonCell
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $extraData = null;


    /**
     * getMethod 
     * 
     * @return \XLite\Model\Shipping\Method
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * getMarkup 
     * 
     * @return \XLite\Model\Shipping\Markup
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setMarkup($markup)
    {
        $this->markup = $markup;
    }

    /**
     * getBaseRate 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setBaseRate($baseRate)
    {
        $this->baseRate = $baseRate;
    }

    /**
     * getMarkupRate 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setMarkupRate($markupRate)
    {
        $this->markupRate = $markupRate;
    }

    /**
     * getExtraData
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setExtraData(\XLite\Core\CommonCell $extraData)
    {
        $this->extraData = $extraData;
    }

    /**
     * getTotalRate 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTotalRate()
    {
        return $this->getBaseRate() + $this->getMarkupRate();
    }

    /**
     * getMethodId 
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMethodId()
    {
        return $this->getMethod()->getMethodId();
    }

    /**
     * getMethodName 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMethodName()
    {
        return $this->getMethod()->getName();
    }

}
