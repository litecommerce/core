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

namespace XLite\Module\CDev\GoogleAnalytics\View;

/**
 * Additional bloc for Checkout success page
 *
 *
 * @ListChild (list="center")
 */
class CheckoutSuccess extends \XLite\View\AView
{
    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();

        $list[] = 'checkoutSuccess';

        return $list;
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/GoogleAnalytics/drupal.tpl';
    }

    /**
     * Get account id from Drupal module
     *
     * @return string
     */
    protected function getAccount()
    {
        return variable_get('googleanalytics_account', '');
    }

    /**
     * Get commands for _gat
     *
     * @return void
     */
    protected function getGatCommands()
    {
        $list = array();

        $orders = \XLite\Core\Session::getInstance()->gaProcessedOrders;
        if (!is_array($orders)) {
            $orders = array();
        }

        $order = $this->getOrder();
        if (!in_array($order->getOrderId(), $orders)) {

            $bAddress = $order->getProfile()->getBillingAddress();
            $city = $bAddress ? $bAddress->getCity() : '';
            $state = ($bAddress && $bAddress->getState()) ? $bAddress->getState()->getState() : '';
            $country = ($bAddress && $bAddress->getCountry()) ? $bAddress->getCountry()->getCountry() : '';

            $tax = $order->getSurchargeSumByType('TAX');
            $shipping = $order->getSurchargeSumByType('SHIPPING');

            $list[] = '\'_addTrans\', '
                . '\'' . $order->getOrderId() . '\', '
                . '\'' . $this->escapeJavascript(\XLite\Core\Config::getInstance()->Company->company_name) . '\', '
                . '\'' . $order->getTotal() . '\', '
                . '\'' . $tax . '\', '
                . '\'' . $shipping . '\', '
                . '\'' . $this->escapeJavascript($city) . '\', '
                . '\'' . $this->escapeJavascript($state) . '\', '
                . '\'' . $this->escapeJavascript($country) . '\'';

            foreach ($order->getItems() as $item) {

                $product = $item->getProduct();
                $category = $product ? $product->getCategory() : null;
                if ($category && $category->getCategoryId()) {
                    $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')
                        ->getCategoryPath($category->getCategoryId());
                    $category = array();
                    foreach ($categories as $cat) {
                        $category[] = $cat->getName();
                    }

                    $category = implode(' / ', $category);

                } else {
                    $category = '';
                }

                $list[] = '\'_addItem\', '
                    . '\'' . $order->getOrderId() . '\', '
                    . '\'' . $this->escapeJavascript($item->getSku()) . '\', '
                    . '\'' . $this->escapeJavascript($item->getName()) . '\', '
                    . '\'' . $this->escapeJavascript($category) . '\', '
                    . '\'' . $item->getPrice() . '\', '
                    . '\'' . $item->getAmount() . '\'';
            }

            $list[] = '\'_trackTrans\'';

            $orders[] = $order->getOrderId();
            \XLite\Core\Session::getInstance()->gaProcessedOrders = $orders;
        }

        return $list;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->isDisplayDrupal();
    }

    /**
     * Display widget as Drupal-specific
     *
     * @return boolean
     */
    protected function isDisplayDrupal()
    {
        return \XLite\Core\Operator::isClassExists('\XLite\Module\CDev\DrupalConnector\Handler')
            && \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            && function_exists('googleanalytics_help')
            && $this->getAccount();
    }

    /**
     * Escape string for Javascript
     *
     * @param string $string String
     *
     * @return string
     */
    protected function escapeJavascript($string)
    {
        return strtr(
            $string,
            array(
                '\\' => '\\\\',
                '\'' => '\\\'',
                '"'  => '\\"',
                "\r" => '\\r',
                "\n" => '\\n',
                '</' =>'<\/'
            )
        );
    }
}
