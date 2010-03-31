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
 * Abstract container widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_View_Container extends XLite_View_Abstract
{
    /**
     * Default body template
     */

    const PARAM_BODY_TEMPLATE = 'body.tpl';


    /**
     * Return title 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getHead();

    /**
     * Return templates directory name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getDir();

    /**
     * Return default template
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getDefaultTemplate();


    /**
     * isWrapper 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isWrapper()
    {
        return $this->getParam(self::PARAM_TEMPLATE) == $this->getDefaultTemplate();
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
        return $this->useBodyTemplate() ? $this->getBody() : parent::getTemplate();
    }

    /**
     * Return file name for the center part template 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getBody()
    {
        return $this->getDir() . LC_DS . self::PARAM_BODY_TEMPLATE;
    }

    /**
     * Determines if need to display only a widget body
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function useBodyTemplate()
    {
        return XLite_Core_CMSConnector::isCMSStarted() && $this->isWrapper();
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

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue($this->getDefaultTemplate());
    }
}

