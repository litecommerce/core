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

namespace XLite\View\Checkout;

/**
 * Checkout steps block
 * 
 * @see   ____class_see____
 * @since 3.0.0
 *
 * @ListChild (list="checkout.main")
 */
class Steps extends \XLite\View\AView
{
    /**
     * Shipping modifier (cache)
     * 
     * @var   \XLite\Model\Order\Modifier
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $shippingModifier;

    /**
     * Steps (cache)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $steps;

    /**
     * Current step 
     * 
     * @var   \XLite\View\Checkout\Step\AStep
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $currentStep;


    /**
     * Get steps 
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSteps()
    {
        if (!isset($this->steps)) {

            $this->steps = array();
            foreach ($this->defineSteps() as $step) {
                $widget = $this->getWidget(
                    array(
                        \XLite\View\Checkout\Step\AStep::PARAM_PARENT_WIDGET => $this,
                    ),
                    $step
                );
                $this->steps[$widget->getStepName()] = $widget;
            }

            foreach ($this->steps as $step) {
                $step->setWidgetParams(
                    array(
                        \XLite\View\Checkout\Step\AStep::PARAM_IS_CURRENT  => $this->isCurrentStep($step),
                        \XLite\View\Checkout\Step\AStep::PARAM_IS_PREVIOUS => $this->isPreviousStep($step),
                    )
                );

            }
        }

        return $this->steps;
    }

    /**
     * Get current step 
     * 
     * @return \XLite\View\Checkout\Step\AStep
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCurrentStep()
    {
        if (!isset($this->currentStep)) {
            foreach ($this->getSteps() as $k => $step) {
                if (!$step->isCompleted() ||  \XLite\Core\Request::getInstance()->step == $k) {
                    $this->currentStep = $step;
                    break;
                }
            }
        }

        return $this->currentStep;
    }

    /**
     * Check - specified step is current or not
     * 
     * @param \XLite\View\Checkout\Step\AStep $step Step
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isCurrentStep(\XLite\View\Checkout\Step\AStep $step)
    {
        return $this->getCurrentStep() == $step;
    }

    /**
     * Check -= specified step is previous or not
     * 
     * @param \XLite\View\Checkout\Step\AStep $step Step
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPreviousStep(\XLite\View\Checkout\Step\AStep $step)
    {
        return !$this->isCurrentStep($step) && $step->isCompleted();
    }

    /**
     * Get current step number 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStepNumber()
    {
        $i = 1;

        foreach ($this->getSteps() as $k => $step) {
            if ($this->isCurrentStep($step)) {
                break;
            }
            $i++;
        }

        return $i;
    }


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'checkout/steps.tpl';
    }

    /**
     * Check - has specified step left arrow or not
     * 
     * @param \XLite\View\Checkout\Step\AStep $step Step
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function hasLeftArrow(\XLite\View\Checkout\Step\AStep $step)
    {
        $steps = $this->getSteps();

        return array_shift($steps) != $step;
    }

    /**
     * Check - has specified step right arrow or not
     * 
     * @param \XLite\View\Checkout\Step\AStep $step Step
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function hasRightArrow(\XLite\View\Checkout\Step\AStep $step)
    {
        $steps = $this->getSteps();

        return array_pop($steps) != $step;
    }

    /**
     * Define checkout widget steps 
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineSteps()
    {
        $steps = array();

        if ($this->getShippingModifier() && $this->getShippingModifier()->canApply()) {
            $steps[] = '\XLite\View\Checkout\Step\Shipping';
        }

        $steps[] = '\XLite\View\Checkout\Step\Payment';
        $steps[] = '\XLite\View\Checkout\Step\Review';
    
        return $steps;
    }

    /**
     * Get modifier 
     * 
     * @return \XLite\Model\Order\Modifier
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getShippingModifier()
    {
        if (!isset($this->shippingModifier)) {
            $this->shippingModifier 
                = $this->getCart()->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');
        }

        return $this->shippingModifier;
    }
}
