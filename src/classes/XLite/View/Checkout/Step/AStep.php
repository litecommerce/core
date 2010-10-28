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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\Checkout\Step;

/**
 * Abstract checkout step widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
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
     * Get step name
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function getStepName();

    /**
     * Get step title
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function getTitle();

    /**
     * Check - step is complete or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function isCompleted();

    /**
     * Check - is current step or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isCurrent()
    {
        return $this->getParam(self::PARAM_PARENT_WIDGET)->isCurrentStep($this);
    }

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'checkout/body.tpl';
    }

    /**
     * Return current template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTemplate()
    {
        return $this->getParam(self::PARAM_TEMPLATE) == $this->getDefaultTemplate()
            ? 'checkout/steps/'
                . $this->getStepName()
                . '/'
                . ($this->isCurrent() ? 'selected' : 'inactive')
                . '.tpl'
            : $this->getParam(self::PARAM_TEMPLATE);
    }
}
