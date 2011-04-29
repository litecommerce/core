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
 * @since     1.0.0
 */

namespace XLite\Module\CDev\GoogleAnalytics\View;

/**
 * Header declaration
 * 
 * @see   ____class_see____
 * @since 1.0.0
 * @ListChild (list="head")
 */
class Header extends \XLite\View\AView
{
    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/GoogleAnalytics/header.tpl';
    }

    /**
     * Get _gaq options list
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getGaqOptions()
    {
        $list = array('\'_setAccount\', \'' . \XLite\Core\Config::getInstance()->GoogleAnalytics->ga_account . '\'');

        if (2 == \XLite\Core\Config::getInstance()->GoogleAnalytics->ga_tracking_type) {
            
            $list[] = '\'_setDomainName\', \'.\' + self.location.host.replace(/^[^\.]+\./, \'\')';

        } elseif (3 == \XLite\Core\Config::getInstance()->GoogleAnalytics->ga_tracking_type) {
            $list[] = '\'_setDomainName\', \'none\'';
            $list[] = '\'_setAllowLinker\', true';
        }

        $list[] = '\'_trackPageview\'';

        $controller = \XLite::getController();

        if ($controller instanceof \XLite\Controller\Customer\CheckoutSuccess) {
            $orders = \XLite\Core\Session::getInstance()->gaProcessedOrders;
            if (!is_array($orders)) {
                $orders = array();
            }            

            $order = $this->getOrder();
            if (!in_array($order->getOrderId(), $orders)) {
                foreach ($order->getItems() as $items) {
                    $list[] = '\'_addItem\', '
                        . '\'' . $order->getOrderId() . '\', '
                        . '\'' . $item->getSku() . '\', '
                        . '\'' . $item->getName() . '\', '
                        . '\'\', '
                        . '\'' . $item->getPrice() . '\', '
                        . '\'' . $item->getAmount() . '\'';
                }

                $bAddress = $order->getProfile()->getBillingAddress();
                $city = $bAddress ? $bAddress->getCity() : '';
                $state = ($bAddress && $bAddress->getState()) ? $bAddress->getState()->getState() : '';
                $country = ($bAddress && $bAddress->getCountry()) ? $bAddress->getCountry()->getCountry() : '';

                $tax = $order->getSurchargeSumByType('TAX');
                $shipping = $order->getSurchargeSumByType('SHIPPING');

                $list[] = '\'_addTrans\', '
                    . '\'' . $order->getOrderId() . '\', '
                    . '\'' . \XLite\Core\Config::getInstance()->Company->company_name . '\', '
                    . '\'' . $order->gettotle() . '\', '
                    . '\'' . $tax . '\', '
                    . '\'' . $shipping . '\', '
                    . '\'' . $city . '\', '
                    . '\'' . $state . '\', '
                    . '\'' . $country . '\'';

                $list[] = '\'_trackTrans\'';

                $orders[] = $order->getOrderId();
                \XLite\Core\Session::getInstance()->gaProcessedOrders = $orders;
            }
        }


        return $list;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean 
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->checkDrupalModule();
    }

    /**
     * Check Drupal module 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkDrupalModule()
    {
        return !\XLite\Core\Operator::isClassExists('\XLite\Module\CDev\DrupalConnector\Handler')
            || !\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            || !function_exists('googleanalytics_help');
    }
}

