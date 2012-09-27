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

namespace XLite\View\Payment;

/**
 * IFRAME-based payment page
 * 
 *
 * @ListChild (list="center")
 */
class Iframe extends \XLite\View\AView
{
    /**
     * Common widget parameter names
     */
    const PARAM_WIDTH  = 'width';
    const PARAM_HEIGHT = 'height';
    const PARAM_SRC    = 'src';


    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $targets = parent::getAllowedTargets();

        $targets[] = 'checkoutPayment';

        return $targets;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && \XLite\Core\Session::getInstance()->iframePaymentData;
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_WIDTH  => new \XLite\Model\WidgetParam\Int('Width', 400),
            self::PARAM_HEIGHT => new \XLite\Model\WidgetParam\Int('Height', 400),
            self::PARAM_SRC    => new \XLite\Model\WidgetParam\String('Source', ''),
        );
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'payment/iframe.tpl';
    }

    /**
     * Set widget params
     *
     * @param array $params Handler params
     *
     * @return void
     */
    public function setWidgetParams(array $params)
    {
        if (is_array(\XLite\Core\Session::getInstance()->iframePaymentData)) {
            $params = array_merge($params, \XLite\Core\Session::getInstance()->iframePaymentData);
        }

        parent::setWidgetParams($params);
    }
}

