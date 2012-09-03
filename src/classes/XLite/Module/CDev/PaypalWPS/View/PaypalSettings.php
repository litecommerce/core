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

namespace XLite\Module\CDev\PaypalWPS\View;

/**
 * Paypal payment method settings dialog
 * 
 */
class PaypalSettings extends \XLite\View\Dialog
{
    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/CDev/PaypalWPS/settings/style.css';

        return $list;
    }

    /**
     * Get settings template directory
     * 
     * @return string
     */
    protected function getDir()
    {
        return 'modules/CDev/PaypalWPS/settings';
    }

    // {{{ Content

    /**
     * Get register URL 
     * 
     * @return string
     */
    protected function getPaypalRegisterURL()
    {
        return 'http://www.paypal.com/';
    }

    // }}}
}
