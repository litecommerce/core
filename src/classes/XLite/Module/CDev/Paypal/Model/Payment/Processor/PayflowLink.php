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

namespace XLite\Module\CDev\Paypal\Model\Payment\Processor;

/**
 * Payflow Link payment processor
 *
 */
class PayflowLink extends \XLite\Module\CDev\Paypal\Model\Payment\Processor\APaypal
{
    /**
     * Referral page URL 
     * 
     * @var string
     */
    protected $referralPageURL = 'https://www.paypal.com/webapps/mpp/referral/paypal-payflow-link?partner_id=';


    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     */
    public function getSettingsTemplateDir()
    {
        return 'modules/CDev/Paypal/settings/payflow_link';
    }

    /**
     * Get the list of merchant countries where this payment processor can work
     *
     * @return array
     */
    public function getAllowedMerchantCountries()
    {
        return array('US', 'CA');
    }

    /**
     * Get allowed currencies
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return array
     */
    protected function getAllowedCurrencies(\XLite\Model\Payment\Method $method)
    {
        return array_merge(
            parent::getAllowedCurrencies($method),
            array(
                'USD', // US Dollar
            )
        );
    }
}
