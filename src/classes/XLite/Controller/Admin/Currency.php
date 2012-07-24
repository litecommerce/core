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

namespace XLite\Controller\Admin;

/**
 * Currency management page controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Currency extends \XLite\Controller\Admin\AAdmin
{
    /**
     * init
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function init()
    {
        if (isset(\XLite\Core\Request::getInstance()->currency_id)) {

            $currency = \XLite\Core\Database::getRepo('XLite\Model\Currency')->find(\XLite\Core\Request::getInstance()->currency_id);

            if ($currency) {

                $shopCurrency = \XLite\Core\Database::getRepo('XLite\Model\Config')
                    ->findOneBy(array('name' => 'shop_currency', 'category' => 'General'));

                \XLite\Core\Database::getRepo('XLite\Model\Config')->update(
                    $shopCurrency,
                    array('value' => $currency->getCurrencyId())
                );
            }
        }
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Currency';
    }

    /**
     * Return currencies collection to use
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCurrencies()
    {
        if (!isset($this->currencies)) {
            $this->currencies = \XLite\Core\Database::getRepo('XLite\Model\Currency')->findAll();
        }

        return $this->currencies;
    }

    /**
     * Modify currency action
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionModify()
    {
        $this->getModelForm()->performAction('modify');
    }

    /**
     * Class name for the \XLite\View\Model\ form
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\Currency\Currency';
    }
}
