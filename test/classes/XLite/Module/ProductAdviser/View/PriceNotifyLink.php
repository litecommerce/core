<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Notify link widget
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
 * Notify link widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_ProductAdviser_View_PriceNotifyLink extends XLite_View_Abstract
{
    /**
     * Widget parameter names
     */

    const PARAM_PRODUCT = 'product';


    /**
     * Price notified flag (cache)
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $priceNotified = null;


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/ProductAdviser/PriceNotification/product_button.tpl';
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
            self::PARAM_PRODUCT => new XLite_Model_WidgetParam_Object('Product', null, false, 'XLite_Model_Product'),
        );
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
            && $this->getParam(self::PARAM_PRODUCT)->isPriceNotificationAllowed()
            && !$this->isPriceNotificationSaved();
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

        $list[] = 'modules/ProductAdviser/PriceNotification/product_button.js';
        $list[] = 'modules/ProductAdviser/notify_me.js';
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
     * Check - price notification is saved or not
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isPriceNotificationSaved()
    {
        if (!$this->config->ProductAdviser->customer_notifications_enabled) {
            $this->priceNotified = true;

        } elseif (is_null($this->priceNotified)) {

            $email = '';
            $profile_id = 0;

            if ($this->auth->is('logged')) {
                $profile = $this->auth->get('profile');
                $profile_id = $profile->get('profile_id');
                $email = $profile->get('login');

            } elseif ($this->session->isRegistered('customerEmail')) {
                $email = $this->session->get('customerEmail');
            }

            $check = array(
                'type = \'' . CUSTOMER_NOTIFICATION_PRICE . '\'',
                'profile_id = \'' . $profile_id . '\'',
                'email = \'' . $email . '\'',
            );

            $notification = new XLite_Module_ProductAdviser_Model_Notification();
            $notification->set('type', CUSTOMER_NOTIFICATION_PRICE);
            $notification->set('product_id', $this->getParam(self::PARAM_PRODUCT)->get('product_id'));
            $check[] = 'notify_key = \'' . addslashes($notification->get('productKey')) . '\'';

            $this->priceNotified = $notification->find(implode(' AND ', $check));
        }

        return $this->priceNotified;
    }
}

