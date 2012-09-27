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

namespace XLite\Module\CDev\TwoCheckout\Model\Payment\Processor;

/**
 * 2Checkout.com processor
 *
 * Find the latest API document here:
 * https://www.2checkout.com/documentation/Advanced_User_Guide.pdf
 *
 */
class TwoCheckout extends \XLite\Model\Payment\Base\WebBased
{
    /**
     * Get operation types
     *
     * @return array
     */
    public function getOperationTypes()
    {
        return array(
            self::OPERATION_SALE,
        );
    }

    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     */
    public function getSettingsWidget()
    {
        return 'modules/CDev/TwoCheckout/config.tpl';
    }

    /**
     * Process return
     *
     * @param \XLite\Model\Payment\Transaction $transaction Return-owner transaction
     *
     * @return void
     */
    public function processReturn(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processReturn($transaction);

        $request = \XLite\Core\Request::getInstance();

        $status = $request->cart_order_id ? $transaction::STATUS_SUCCESS : $transaction::STATUS_FAILED;

        // Checking MD5. /Secret/Account/OrderId/Total cost/
        $orderId = 'test' === $this->getSetting('mode') ? 1 : $request->order_number;

        $calculated = $this->getSetting('secret')
            . $this->getSetting('account')
            . $orderId
            . $this->getFormattedPrice($request->total);

        $calculated = strtoupper(md5($calculated));

        if ($calculated != $request->key) {

            $status = $transaction::STATUS_FAILED;

            $this->setDetail('verification', 'MD5 verification failed', 'Verification');

            $this->transaction->setNote('MD5 verification failed');
        }

        // Checking total cost value
        if (!$this->checkTotal($request->total)) {

            $status = $transaction::STATUS_FAILED;
        }

        $this->transaction->setStatus($status);
    }

    /**
     * Check - payment method is configured or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured($method)
            && $method->getSetting('account')
            && $method->getSetting('secret');
    }

    /**
     * Get return type
     *
     * @return string
     */
    public function getReturnType()
    {
        return self::RETURN_TYPE_HTML_REDIRECT;
    }

    /**
     * Returns the list of settings available for this payment processor
     *
     * @return array
     */
    public function getAvailableSettings()
    {
        return array(
            'account',
            'secret',
            'language',
            'mode',
            'prefix',
            'currency',
        );
    }

    /**
     * Get return request owner transaction or null
     *
     * @return \XLite\Model\Payment\Transaction|void
     */
    public function getReturnOwnerTransaction()
    {
        $transactionId = \XLite\Core\Request::getInstance()->cart_order_id;

        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')
            ->find($transactionId);
    }

    /**
     * Get payment method admin zone icon URL
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getAdminIconURL(\XLite\Model\Payment\Method $method)
    {
        return true;
    }


    /**
     * Get redirect form URL
     *
     * @return string
     */
    protected function getFormURL()
    {
        return 'https://www.2checkout.com/checkout/spurchase';
    }

    /**
     * Format name for request. (firstname + lastname from shipping/billing address)
     *
     * @param \XLite\Model\Address $address Address model (could be shipping or billing address)
     *
     * @return string
     */
    protected function getName($address)
    {
        return $address->getFirstname()
            . ' ' . $address->getLastname();
    }

    /**
     * Format state of billing address for request
     *
     * @return string
     */
    protected function getBillingState()
    {
        return $this->getState($this->getProfile()->getBillingAddress());
    }

    /**
     * Format state of shipping address for request
     *
     * @return string
     */
    protected function getShippingState()
    {
        return $this->getState($this->getProfile()->getShippingAddress());
    }

    /**
     * Format state that is provided from $address model for request.
     *
     * @param \XLite\Model\Address $address Address model (could be shipping or billing address)
     *
     * @return string
     */
    protected function getState($address)
    {
        $state = $this->getStateFieldValue($address);

        if (empty($state)) {
            $state = 'n/a';
        } elseif (!in_array($this->getCountryField($address), array('US', 'CA'))) {
            $state = 'XX';
        }

        return $state;
    }

    /**
     * Return State field value. If country is US then state code must be used.
     *
     * @param \XLite\Model\Address $address Address model (could be shipping or billing address)
     *
     * @return string
     */
    protected function getStateFieldValue($address)
    {
        return 'US' === $this->getCountryField($address)
            ? $address->getState()->getCode()
            : $address->getState()->getState();
    }

    /**
     * Return Country field value. if no country defined we should use '' value
     *
     * @param \XLite\Model\Address $address Address model (could be shipping or billing address)
     *
     * @return string
     */
    protected function getCountryField($address)
    {
        return $address->getCountry()
            ? $address->getCountry()->getCode()
            : '';
    }

    /**
     * Return formatted price.
     *
     * @param integer $price Price value
     *
     * @return string
     */
    protected function getFormattedPrice($price)
    {
        return sprintf('%.2f', round((double)($price) + 0.00000000001, 2));
    }


    /**
     * Get redirect form fields list
     *
     * @return array
     */
    protected function getFormFields()
    {
        $fields = array(
            'sid'                   => $this->getSetting('account'),
            'total'                 => $this->getFormattedPrice($this->transaction->getValue()),
            'cart_order_id'         => $this->transaction->getTransactionId(),
            'merchant_order_id'     => $this->getSetting('prefix') . $this->getOrder()->getOrderId(),
            'pay_method'            => 'CC',
            'lang'                  => $this->getSetting('language'),
            'skip_landing'          => '1',
            'card_holder_name'      => $this->getName($this->getProfile()->getBillingAddress()),
            'street_address'        => $this->getProfile()->getBillingAddress()->getStreet(),
            'city'                  => $this->getProfile()->getBillingAddress()->getCity(),
            'state'                 => $this->getBillingState(),
            'zip'                   => $this->getProfile()->getBillingAddress()->getZipcode(),
            'country'               => $this->getCountryField($this->getProfile()->getBillingAddress()),
            'email'                 => $this->getProfile()->getLogin(),
            'phone'                 => $this->getProfile()->getBillingAddress()->getPhone(),
            'fixed'                 => 'Y',
            'id_type'               => '1',
            'sh_cost'               => $this->getFormattedPrice($this->getOrder()->getSurchargeSumByType('SHIPPING')),
        );

        if ($shippingAddress = $this->getProfile()->getShippingAddress()) {

            $fields += array(
                'ship_name'             => $this->getName($shippingAddress),
                'ship_street_address'   => $shippingAddress->getStreet(),
                'ship_city'             => $shippingAddress->getCity(),
                'ship_state'            => $this->getShippingState(),
                'ship_zip'              => $shippingAddress->getZipcode(),
                'ship_country'          => $this->getCountryField($shippingAddress),
            );
        }

        if ('test' === $this->getSetting('mode')) {

            $fields['demo'] = 'Y';
        }

        $i = -1;

        foreach ($this->getOrder()->getItems() as $item) {

            $product = $item->getProduct();

            $i++;
            $suffix = 0 == $i ? '' : ('_' . $i);

            $description = $product->getCommonDescription() ?: $product->getName();

            $fields['c_prod' . $suffix]        = $product->getProductId() . ',' . $item->getAmount();
            $fields['c_name' . $suffix]        = substr($product->getName(), 0, 127);
            $fields['c_price' . $suffix]       = $this->getFormattedPrice($item->getPrice());
            $fields['c_description' . $suffix] = strip_tags(substr(($description), 0, 254));
        }

        return $fields;
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
            array($method->getSetting('currency'))
        );
    }

}
