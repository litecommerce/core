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

namespace XLite\Module\ProductAdviser\View;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @ListChild (list="productDetails.main", weight="60")
 * @ListChild (list="quickLook.main", weight="60")
 */
class PriceNotifyLink extends \XLite\View\AView
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
            self::PARAM_PRODUCT => new \XLite\Model\WidgetParam\Object('Product', $this->getProduct(), false, '\XLite\Model\Product'),
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
    protected function isVisible()
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
        $list[] = 'js/jquery.blockUI.js';
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

            $notification = new \XLite\Module\ProductAdviser\Model\Notification();
            $notification->set('type', CUSTOMER_NOTIFICATION_PRICE);
            $notification->set('product_id', $this->getParam(self::PARAM_PRODUCT)->get('product_id'));
            $check[] = 'notify_key = \'' . addslashes($notification->get('productKey')) . '\'';

            $this->priceNotified = $notification->find(implode(' AND ', $check));
        }

        return $this->priceNotified;
    }
}
