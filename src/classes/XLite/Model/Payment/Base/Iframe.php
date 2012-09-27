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

namespace XLite\Model\Payment\Base;

/**
 * Abstract credit card, web-based (iframe) processor
 *
 */
abstract class Iframe extends \XLite\Model\Payment\Base\CreditCard
{
    /**
     * Payment widget data 
     * 
     * @var array
     */
    protected $paymentWidgetData = array();

    /**
     * Get iframe data
     *
     * @return string|array URL or POST data
     */
    abstract protected function getIframeData();

    /**
     * Get input template
     *
     * @return string|void
     */
    public function getInputTemplate()
    {
        return null;
    }

    /**
     * Get return request owner transaction or null
     *
     * @return \XLite\Model\Payment\Transaction|void
     */
    public function getReturnOwnerTransaction()
    {
        return null;
    }

    /**
     * Get payment widget data 
     * 
     * @return array
     */
    public function getPaymentWidgetData()
    {
        return $this->paymentWidgetData;
    }

    /**
     * Get iframe form URL
     *
     * @return string
     */
    protected function getIframeFormURL()
    {
    }

    /**
     * Get iframe size 
     * 
     * @return array
     */
    protected function getIframeSize()
    {
        return array(600, 400);
    }

    /**
     * Do initial payment
     *
     * @return string Status code
     */
    protected function doInitialPayment()
    {
        $this->transaction->createBackendTransaction($this->getInitialTransactionType());

        $data = $this->getIframeData();

        if (isset($data)) {

            list($width, $height) = $this->getIframeSize();

            \XLite\Core\Session::getInstance()->iframePaymentData = array(
                'width'  => $width,
                'height' => $height,
                'src'    => is_array($data) ? $this->assembleFormIframe($data) : $this->assembleURLIframe($data),
            );

            $status = self::SEPARATE;

        } else {
            $this->setDetail(
                'iframe_data_error',
                'Payment processor \'' . get_called_class() . '\' did not assemble service data successfull.'
            );
            $status = self::FAILED;
            $this->transaction->setNote('Payment is failed');
        }

        return $status;
    }

    /**
     * Assemble form-based iframe 
     * 
     * @param array $data Form elements
     *  
     * @return string
     */
    protected function assembleFormIframe(array $data)
    {
        $content = new \XLite\Model\IframeContent;
        $content->setData($data);
        $content->setUrl($this->getIframeFormURL());

        \XLite\Core\Database::getEM()->persist($content);
        \XLite\Core\Database::getEM()->flush();

        return \XLite\Core\Converter::buildURL('iframe_content', '', array('id' => $content->getId()));
    }

    /**
     * Assemble URL-based iframe 
     * 
     * @param string $data Iframe URL
     *  
     * @return string
     */
    protected function assembleURLIframe($data)
    {
        return $data;
    }
}
