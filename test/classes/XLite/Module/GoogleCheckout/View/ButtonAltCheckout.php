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
 * Google button
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GoogleCheckout_View_ButtonAltCheckout extends XLite_View_Button
    {

    /**
     * Widget param names
     */
    const PARAM_SIZE  = 'size';
    const PARAM_BACKGROUND = 'background';

    public $buttonUrl = null;
    public $gacObject = null;

    function init()
    {
        if (!isset($this->gacObject)) {
    		$this->gacObject = new XLite_Module_GoogleCheckout_View_GoogleAltCheckout();
        	$this->gacObject->initGoogleData();
        }

        if (isset($this->gacObject->GCMerchantID) && $this->getComplex('dialog.target') == "cart" && strtolower($this->get("label")) == "checkout") {
            $this->template = "modules/GoogleCheckout/button_alt_checkout.tpl";
        }

        parent::init();
    }

    function getGoogleCheckoutButtonUrl()
    {
        if (!isset($this->buttonUrl)) {
        	$this->buttonUrl = $this->gacObject->getGoogleCheckoutButtonUrl($this->getParam(self::PARAM_SIZE), $this->getParam(self::PARAM_BACKGROUND));
        }

        return $this->buttonUrl;
    }

    function isGoogleAllowPay()
    {
        return $this->gacObject->isGoogleAllowPay();
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
            self::PARAM_SIZE => new XLite_Model_WidgetParam_String(
                'Button size', 'medium', false
            ),
            self::PARAM_BACKGROUND => new XLite_Model_WidgetParam_String(
                'Background (white/transparent)', 'white', false
            ),
        );
    }

}
