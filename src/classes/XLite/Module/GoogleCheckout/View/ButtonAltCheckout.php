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

namespace XLite\Module\GoogleCheckout\View;

/**
 * Google button
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ButtonAltCheckout extends \XLite\View\Button
{

    /**
     * Widget param names
     */
    const PARAM_SIZE  = 'size';
    const PARAM_BACKGROUND = 'background';

    /**
     * Button mage URL 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $buttonUrl = null;

    /**
     * Service object
     * 
     * @var    \XLite\Module\GoogleCheckout\View\GoogleAltCheckout
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $gacObject = null;

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/GoogleCheckout/shopping_cart/button.tpl';
    }

    /**
     * Get Google Checkout button image URL 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGoogleCheckoutButtonUrl()
    {
        if (is_null($this->buttonUrl)) {
            $this->buttonUrl = $this->getGacObject()->getGoogleCheckoutButtonUrl(
                $this->getParam(self::PARAM_SIZE),
                $this->getParam(self::PARAM_BACKGROUND)
            );
        }

        return $this->buttonUrl;
    }

    /**
     * Check - allow pay with Google checkout or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isGoogleAllowPay()
    {
        return $this->getGacObject()->isGoogleAllowPay();
    }

    /**
     * Get service object 
     * 
     * @return \XLite\Module\GoogleCheckout\View\GoogleAltCheckout
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGacObject()
    {
        if (is_null($this->gacObject)) {
            $this->gacObject = new \XLite\Module\GoogleCheckout\View\GoogleAltCheckout();
            $this->gacObject->initGoogleData();
        }

        return $this->gacObject;
    }

    /**
     * Check widget vidibility
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible()
            && $this->getGacObject()->GCMerchantID
            && $this->getGacObject()->GCMerchantKey;
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
            self::PARAM_SIZE => new \XLite\Model\WidgetParam\String(
                'Button size', 'medium', false
            ),
            self::PARAM_BACKGROUND => new \XLite\Model\WidgetParam\String(
                'Background (white/transparent)', 'white', false
            ),
        );
    }

}
