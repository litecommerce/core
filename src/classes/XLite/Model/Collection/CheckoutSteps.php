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

namespace XLite\Model\Collection;

/**
 * Checkout steps list
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class CheckoutSteps extends \XLite\Model\Collection
{
    /**
     * current 
     * 
     * @var   \XLite\Model\ListNode\CheckoutStep
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $current = null;

    /**
     * actual 
     * 
     * @var   \XLite\Model\ListNode\CheckoutStep
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $actual = null;


    /**
     * Return current (so called "regular") step
     * 
     * @return \XLite\Model\ListNode\CheckoutStep
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCurrentStep()
    {
        return $this->getStep(false, 'correctStep');
    }

    /**
     * Return actual ("regular" or "pseudo") checkout step
     * 
     * @return \XLite\Model\ListNode\CheckoutStep
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getActualStep()
    {
        return $this->getStep(true);
    }

    /**
     * Check if the step was corrected
     * 
     * @return boolean 
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isCorrectedStep()
    {
        return $this->getCurrentStep() != $this->getActualStep();
    }


    /**
     * findLastPassedRegularStep 
     * 
     * @param \XLite\Model\ListNode\CheckoutStep &$step Object to prepare
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function findLastPassedRegularStep(\XLite\Model\ListNode\CheckoutStep &$step)
    {
        while ($step && !$step->isRegularStep()) {
            $step = $step->getPrev();
        }
    }

    /**
     * correctStep 
     * 
     * @param \XLite\Model\ListNode\CheckoutStep &$step Object to prepare
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function correctStep(\XLite\Model\ListNode\CheckoutStep &$step)
    {
        if (isset($step) && !$step->isPassed()) {
            $this->findLastPassedRegularStep($step);
        }
    }

    /**
     * getStep 
     * 
     * @param boolean $isActual Flag to determine step type
     * @param string  $method   Name of the callback function used to prepare step object OPTIONAL
     *  
     * @return \XLite\Model\ListNode\CheckoutStep
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getStep($isActual, $method = null)
    {
        $name = $isActual ? 'actual' : 'current';

        if (!isset($this->$name)) {
            $this->$name = $this->findByCallbackResult('checkMode', array(\XLite\Core\Request::getInstance()->mode));

            if (isset($method)) {
                // $method is method argument. See getCurrentStep() method
                $this->$method($this->$name);
            }
        }

        return $this->$name;
    }
}
