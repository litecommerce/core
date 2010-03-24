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

/**
 * Sort form
 * 
 * @package    XLite
 * @subpackage View
 * @since      3.0.0
 */
class XLite_View_Form_Sort extends XLite_View_Form_Abstract
{
    /**
     * Widget parameter names
     */

    const PARAM_PARAMS = 'params';


    /**
     * Current form name
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getFormName()
    {
        return 'sort_search';
    }

    /**
     * getDefaultTarget 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTarget()
    {
        return 'order_list';
    }

    /**
     * getDefaultAction 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultAction()
    {
        return 'search';
    }

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
            self::PARAM_PARAMS => new XLite_Model_WidgetParam_Array('Parameters', array()),
        );

        $this->widgetParams[self::PARAM_CLASS_NAME]->setValue('sort-box');
    }

    /**
     * Initialization 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function initView()
    {
        parent::initView();

        $this->widgetParams[self::PARAM_FORM_PARAMS]->appendValue($this->getFormDefaultParams());
    }

    /**
     * getFormDefaultParams 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFormDefaultParams()
    {
        return $this->getParam(self::PARAM_PARAMS);
    }


}

