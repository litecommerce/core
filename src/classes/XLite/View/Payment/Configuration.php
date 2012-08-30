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
 * Payment configuration page
 */
class Configuration extends \XLite\View\AView
{
    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'payment/configuration/style.css';

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

        $list[] = 'payment/configuration/controller.js';

        return $list;
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'payment/configuration/body.tpl';
    }

    // {{{ Content helpers

    /**
     * Check - has active payment modules 
     * 
     * @return boolean
     */
    protected function hasPaymentModules()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->hasActivePaymentModules();
    }

    /**
     * Check - has installed all-in-one and acc gateways payment modules or not
     * 
     * @return boolean
     */
    protected function hasGateways()
    {
        $cnd = new \XLite\Core\CommonCell;
        $cnd->{\XLite\Model\Repo\Payment\Method::P_TYPE} = array(
            \XLite\Model\Payment\Method::TYPE_ALLINONE,
            \XLite\Model\Payment\Method::TYPE_CC_GATEWAY
        );

        return 0 < \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->search($cnd, true);
    }

    /**
     * Check - has added all-in-one and cc gateways payment modules or not
     *
     * @return boolean
     */
    protected function hasAddedGateways()
    {
        $cnd = new \XLite\Core\CommonCell;
        $cnd->{\XLite\Model\Repo\Payment\Method::P_ADDED} = true;
        $cnd->{\XLite\Model\Repo\Payment\Method::P_TYPE} = array(
            \XLite\Model\Payment\Method::TYPE_ALLINONE,
            \XLite\Model\Payment\Method::TYPE_CC_GATEWAY
        );

        return 0 < \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->search($cnd, true);
    }

    /**
     * Get not added all-in-one and cc gateways payment modules count
     *
     * @return integer
     */
    protected function countNonAddedGateways()
    {
        $cnd = new \XLite\Core\CommonCell;
        $cnd->{\XLite\Model\Repo\Payment\Method::P_ADDED} = false;
        $cnd->{\XLite\Model\Repo\Payment\Method::P_TYPE} = array(
            \XLite\Model\Payment\Method::TYPE_ALLINONE,
            \XLite\Model\Payment\Method::TYPE_CC_GATEWAY
        );

        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->search($cnd, true);
    }

    /**
     * Check - has installed alternative payment modules or not
     *
     * @return boolean
     */
    protected function hasAlternative()
    {
        $cnd = new \XLite\Core\CommonCell;
        $cnd->{\XLite\Model\Repo\Payment\Method::P_TYPE} = \XLite\Model\Payment\Method::TYPE_ALTERNATIVE;

        return 0 < \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->search($cnd, true);
    }

    /**
     * Check - has added alternative payment modules or not
     *
     * @return boolean
     */
    protected function hasAddedAlternative()
    {
        $cnd = new \XLite\Core\CommonCell;
        $cnd->{\XLite\Model\Repo\Payment\Method::P_ADDED} = true;
        $cnd->{\XLite\Model\Repo\Payment\Method::P_TYPE} = \XLite\Model\Payment\Method::TYPE_ALTERNATIVE;

        return 0 < \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->search($cnd, true);
    }

    /**
     * Get not added all-in-one and cc gateways payment modules count
     *
     * @return integer
     */
    protected function countNonAddedAlternative()
    {
        $cnd = new \XLite\Core\CommonCell;
        $cnd->{\XLite\Model\Repo\Payment\Method::P_ADDED} = false;
        $cnd->{\XLite\Model\Repo\Payment\Method::P_TYPE} = \XLite\Model\Payment\Method::TYPE_ALTERNATIVE;

        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->search($cnd, true);
    }

    /**
     * Get video URL 
     * 
     * @return string
     */
    protected function getVideoURL()
    {
        return 'http://www.paypal.com/understandingonlinepayments';
    }
    // }}}
}
