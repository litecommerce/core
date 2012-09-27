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

namespace XLite\View\Checkout\Step;

/**
 * Abstract checkout step widget
 *
 */
abstract class AStep extends \XLite\View\AView
{
    /**
     * Common widget parameter names
     */
    const PARAM_PARENT_WIDGET = 'parentWidget';
    const PARAM_IS_CURRENT    = 'isCurrent';
    const PARAM_IS_PREVIOUS   = 'isPrevious';

    /**
     * Get step name
     *
     * @return string
     */
    abstract public function getStepName();

    /**
     * Get step title
     *
     * @return string
     */
    abstract public function getTitle();

    /**
     * Check - step is complete or not
     *
     * @return boolean
     */
    abstract public function isCompleted();

    /**
     * Check - is current step or not
     *
     * @return boolean
     */
    public function isCurrent()
    {
        return $this->getParam(self::PARAM_PARENT_WIDGET)->isCurrentStep($this);
    }

    /**
     * Get steps collector
     *
     * @return \XLite\View\Checkout\Steps
     */
    public function getStepsCollector()
    {
        return $this->getParam(self::PARAM_PARENT_WIDGET);
    }

    /**
     * Check - step is enabled (true) or skipped (false)
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_PARENT_WIDGET => new \XLite\Model\WidgetParam\Object('Parent widget', null, false, '\XLite\View\Checkout\Steps'),
            self::PARAM_IS_CURRENT    => new \XLite\Model\WidgetParam\Bool('Step is current', false),
            self::PARAM_IS_PREVIOUS   => new \XLite\Model\WidgetParam\Bool('Step is previous', false),
        );
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'checkout/body.tpl';
    }

    /**
     * Return current template
     *
     * @return string
     */
    protected function getTemplate()
    {
        return $this->getParam(self::PARAM_TEMPLATE) == $this->getDefaultTemplate()
            ? $this->getStepTemplate()
            : $this->getParam(self::PARAM_TEMPLATE);
    }

    /**
     * Get step template
     *
     * @return string
     */
    protected function getStepTemplate()
    {
        $path = 'checkout/steps/' . $this->getStepName() . '/';

        if (!$this->isEnabled()) {
            $path .= 'disabled.tpl';

        } elseif ($this->isCurrent()) {
            $path .= 'selected.tpl';

        } else {
            $path .= 'inactive.tpl';
        }

        return $path;
    }
}
