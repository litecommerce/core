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

namespace XLite\View;

/**
 * Cart widget
 *
 *
 * @ListChild (list="center")
 */
class Cart extends \XLite\View\Dialog
{
    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'cart';

        return $result;
    }

    /**
     * Get continue URL
     *
     * @return string
     */
    public function getContinueURL()
    {
        $url = \XLite\Core\Session::getInstance()->continueURL;

        if (!$url && isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        }

        if (!$url) {
            $url = $this->buildURL('main');
        }

        return $url;
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/cart.css';

        return $list;
    }

    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/controller.js';

        return $list;
    }

    /**
     * Register files from common repository
     *
     * @return array
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list['js'][] = 'js/jquery.blockUI.js';

        return $list;
    }

    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir()
    {
        return 'shopping_cart';
    }

    /**
     * Return file name for body template
     *
     * @return void
     */
    protected function getBodyTemplate()
    {
        return $this->getCart()->isEmpty() ? 'empty.tpl' : parent::getBodyTemplate();
    }

    // {{{ Surcharges

    /**
     * Get surcharge totals 
     * 
     * @return array
     */
    protected function getSurchargeTotals()
    {
        return $this->getCart()->getSurchargeTotals();
    }

    /**
     * Get surcharge class name 
     * 
     * @param string $type      Surcharge type
     * @param array  $surcharge Surcharge
     *  
     * @return string
     */
    protected function getSurchargeClassName($type, array $surcharge)
    {
        return 'order-modifier '
            . $type . '-modifier '
            . strtolower($surcharge['code']) . '-code-modifier';
    }

    /**
     * Format surcharge value
     * 
     * @param array $surcharge Surcharge
     *  
     * @return string
     */
    protected function formatSurcharge(array $surcharge)
    {
        return $this->formatPrice(abs($surcharge['cost']), $this->getCart()->getCurrency());
    }

    /**
     * Get exclude surcharges by type 
     * 
     * @param string $type Surcharge type
     *  
     * @return array
     */
    protected function getExcludeSurchargesByType($type)
    {
        return $this->getCart()->getExcludeSurchargesByType($type);
    }

    // }}}

}
