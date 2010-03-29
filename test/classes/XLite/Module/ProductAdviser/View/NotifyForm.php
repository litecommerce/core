<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Notify form widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Notify form widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_ProductAdviser_View_NotifyForm extends XLite_View_Abstract
{
    /**
     * Widget parameter names
     */

    const PARAM_PRODUCT = 'product';


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

        $this->widgetParams[self::PARAM_PRODUCT] = new XLite_Model_WidgetParam_Object('Product', null, false, 'XLite_Model_Product');
        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('modules/ProductAdviser/OutOfStock/notify_form.tpl');
    }

    /**
     * Check visibility 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible()
            && $this->getParam(self::PARAM_PRODUCT)
            && $this->xlite->get('PA_InventorySupport')
            && $this->get('productNotificationEnabled')
            && $this->get('rejectedItem');
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'modules/ProductAdviser/OutOfStock/notify_form.js';
        $list[] = 'popup/jquery.blockUI.js';
        $list[] = 'popup/popup.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'popup/popup.css';

        return $list;
    }

    /**
     * Get current URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCurrentURL()
    {
        return urlencode(urlencode($this->get('url')));
    }
}

