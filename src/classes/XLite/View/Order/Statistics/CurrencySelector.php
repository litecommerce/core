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

namespace XLite\View\Order\Statistics;

/**
 * Currency selector
 *
 */
class CurrencySelector extends \XLite\View\AView
{
    /**
     * Current currency
     *
     * @var \XLite\Model\Currency
     */
    protected $currency;

    /**
     * Currencies (cache)
     *
     * @var array
     */
    protected $currencies;

    /**
     * Get currencies
     *
     * @return array
     */
    protected function getCurrencies()
    {
        if (!isset($this->currencies)) {
            $this->currencies = parent::getCurrencies();
        }

        return $this->currencies;
    }

    /**
     * Check - currency is selected or not
     *
     * @param \XLite\Model\Currency $currency Currency
     *
     * @return boolean
     */
    protected function isCurrencySelected(\XLite\Model\Currency $currency)
    {
        if (!isset($this->currency)) {
            if (\XLite\Core\Request::getInstance()->currency) {
                $this->currency = \XLite\Core\Database::getRepo('XLite\Model\Currency')->find(\XLite\Core\Request::getInstance()->currency);
            }

            if (!$this->currency) {
                $this->currency = \XLite::getInstance()->getCurrency();
            }
        }

        return $currency->getCurrencyId() == $this->currency->getCurrencyId();
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'order/currency_selector.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return 1 < count($this->getCurrencies());
    }
}
